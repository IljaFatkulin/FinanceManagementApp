<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Route;
use App\Core\Controller;
use App\Core\Http\HttpRequest;
use App\Core\Security\Security;
use App\Exceptions\CategoryNotFoundException;
use App\Exceptions\ForbiddenException;
use App\Exceptions\InvalidArgumentHttpException;
use App\Exceptions\NotFoundException;
use App\Exceptions\UserNotFound;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Requests\TransactionCreateRequest;
use App\Requests\TransactionDeleteRequest;
use App\Requests\TransactionUpdateRequest;
use App\Services\TransactionService;

class TransactionController extends Controller
{
    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        parent::__construct();
        $this->transactionService = $transactionService;
    }

    /**
     * @throws CategoryNotFoundException
     * @throws UserNotFound
     * @throws InvalidArgumentHttpException
     */
    #[Route('/transaction', 'POST')]
    public function create(): void
    {
        $requestBody = HttpRequest::getBody();

        if(!$this->validate(TransactionCreateRequest::class, $requestBody)) return;

        $transactionType = TransactionType::tryFrom($requestBody['type']);
        if($transactionType === null) {
            throw new InvalidArgumentHttpException("Type is invalid. Must be income or expense");
        }

        $transaction = new Transaction(null, null, null, $transactionType, floatval($requestBody['amount']), $requestBody['description'], new \DateTime($requestBody['transaction_date']));
        $this->response($this->transactionService->create($transaction, intval($requestBody['user_id']), intval($requestBody['category_id'])));
    }

    /**
     * @throws InvalidArgumentHttpException
     * @throws UserNotFound
     * @throws CategoryNotFoundException
     */
    #[Route('/transactions')]
    public function getAll(): void
    {
        $params = HttpRequest::getAllParams();

        if(!isset($params['user'])) {
            throw new InvalidArgumentHttpException("User id is required");
        }

        if(isset($params['category'])) {
            $transactions = $this->transactionService->getAllByUserAndCategory(intval($params['user']), intval($params['category']));
        } else {
            $transactions = $this->transactionService->getAllByUser(intval($params['user']));
        }

        $this->response($transactions);
    }

    /**
     * @throws InvalidArgumentHttpException
     */
    #[Route('/transactions/sum')]
    public function getTransactionsSumByCategory(): void
    {
        $params = HttpRequest::getAllParams();

        if(!isset($params['user'])) {
            throw new InvalidArgumentHttpException("User id is required");
        }

        $this->response($this->transactionService->getTransactionsSumByCategory(intval($params['user'])));
    }

    /**
     * @throws \Exception
     */
    #[Route('/transaction')]
    public function findById(): void
    {
        $id = HttpRequest::getParam('id');

        $transaction = $this->transactionService->findById(intval($id));
        if($transaction->getUser()->getEmail() !== Security::getUsername()) {
            throw new ForbiddenException();
        }

        $this->response($transaction);
    }

    #[Route('/transaction/delete', 'POST')]
    public function deleteById(): void
    {
        $requestBody = HttpRequest::getBody();

        if(!$this->validate(TransactionDeleteRequest::class, $requestBody)) return;

        $this->transactionService->deleteById(intval($requestBody['id']));
    }

    /**
     * @throws InvalidArgumentHttpException
     * @throws NotFoundException
     */
    #[Route('/transaction/update', 'POST')]
    public function update(): void
    {
        $requestBody = HttpRequest::getBody();

        if(!$this->validate(TransactionUpdateRequest::class, $requestBody)) return;

        $transactionType = TransactionType::tryFrom($requestBody['type']);
        if($transactionType === null) {
            throw new InvalidArgumentHttpException("Type is invalid. Must be income or expense");
        }

        $transaction = new Transaction(intval($requestBody['id']), null, null, $transactionType, $requestBody['amount'], $requestBody['description'], new \DateTime($requestBody['transaction_date']));
        $this->response($this->transactionService->update($transaction));
    }
}