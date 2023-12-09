<?php
declare(strict_types=1);

namespace App\Core;

use App\Attributes\Validation;
use App\Exceptions\AbstractExceptionWithHttpStatus;
use App\Exceptions\ForbiddenException;

abstract class Controller
{
    public function __construct()
    {
        set_exception_handler(array($this, 'exceptionHandler'));
    }

    public function exceptionHandler(\Throwable $e): void
    {
        if($e instanceof AbstractExceptionWithHttpStatus) {
            $this->response($e->getMessage(), $e->getHttpStatus());
        } else {
            echo $e;
        }
    }

    protected function response($data, $status = 200): void
    {
        echo json_encode($data);
        http_response_code($status);
    }

    protected function validate($model, $requestBody): bool
    {
        if(class_exists($model)) {
            $reflectionModel = new \ReflectionClass($model);

            $errors = [];

            foreach ($reflectionModel->getProperties() as $property) {
                $attributes = $property->getAttributes(Validation::class);
                $propertyType = $property->getType()->getName();

                foreach ($attributes as $attribute) {
                    $validation = $attribute->newInstance();
                    $propertyName = $property->getName();

                    if($validation->required && !isset($requestBody[$propertyName])) {
                        $errors[] = $validation->errorMessage;
                        continue;
                    }

                    $actualType = $this->getNormalizedType($requestBody[$propertyName]);
                    if($actualType !== $propertyType) {
                        $errors[] = "Invalid type for property '{$propertyName}': Expected {$propertyType}, but got {$actualType}";
                    }
                }
            }

            if(count($errors) > 0) {
                $this->response($errors, 400);
                return false;
            }

            return true;
        }
        return true;
    }

    private function getNormalizedType($value): string
    {
        $type = gettype($value);
        return match ($type) {
            'integer' => 'int',
            'double' => 'float',
            'boolean' => 'bool',
            default => $type,
        };
    }
}