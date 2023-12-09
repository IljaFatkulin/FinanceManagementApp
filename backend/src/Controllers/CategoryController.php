<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Route;
use App\Core\Controller;
use App\Core\Http\HttpRequest;
use App\Core\Security\Security;
use App\Exceptions\CategoryNotFoundException;
use App\Exceptions\DuplicateKeyException;
use App\Exceptions\InvalidArgumentHttpException;
use App\Exceptions\UserNotFound;
use App\Models\Category;
use App\Requests\CategoryCreateRequest;
use App\Requests\CategoryDeleteRequest;
use App\Requests\CategoryRenameRequest;
use App\Services\CategoryService;
use App\Services\UserService;

class CategoryController extends Controller
{
    private CategoryService $categoryService;
    private UserService $userService;

    public function __construct(CategoryService $categoryService, UserService $userService)
    {
        parent::__construct();
        $this->categoryService = $categoryService;
        $this->userService = $userService;
    }

    /**
     * @throws UserNotFound
     */
    #[Route('/categories')]
    public function getAll(): void
    {
        $user = $this->userService->findByEmail(Security::getUsername());
        $categories = $this->categoryService->getAllByUser($user);
        $this->response($categories);
    }

    /**
     * @throws InvalidArgumentHttpException
     * @throws CategoryNotFoundException
     */
    #[Route('/category')]
    public function find(): void
    {
        $params = HttpRequest::getAllParams();

        if(!isset($params['name']) && !isset($params['id'])) {
            throw new InvalidArgumentHttpException("No search param");
        }
        if(isset($params['name']) && isset($params['id'])) {
            throw new InvalidArgumentHttpException("Allowed only 1 param");
        }

        if(isset($params['id'])) {
            $this->response($this->categoryService->findById(intval($params['id'])));
        } else if(isset($params['name'])) {
            $this->response($this->categoryService->findByName($params['name']));
        }
    }

    /**
     * @throws DuplicateKeyException
     * @throws UserNotFound
     */
    #[Route('/category', 'POST')]
    public function create(): void
    {
        $requestBody = HttpRequest::getBody();

        if(!$this->validate(CategoryCreateRequest::class, $requestBody)) return;

        $user = $this->userService->findByEmail(Security::getUsername());
        $category = new Category(null, $requestBody['name']);
        $this->response($this->categoryService->create($category, $user));
    }

    /**
     * @throws CategoryNotFoundException
     * @throws DuplicateKeyException
     */
    #[Route('/category/rename', 'POST')]
    public function rename(): void
    {
        $requestBody = HttpRequest::getBody();

        if(!$this->validate(CategoryRenameRequest::class, $requestBody)) return;

        $this->response($this->categoryService->rename(intval($requestBody['id']), $requestBody['new_name']));
    }

    #[Route('/category/delete', 'POST')]
    public function deleteById(): void
    {
        $requestBody = HttpRequest::getBody();

        if(!$this->validate(CategoryDeleteRequest::class, $requestBody)) return;

        $this->categoryService->deleteById($requestBody['id']);
    }
}