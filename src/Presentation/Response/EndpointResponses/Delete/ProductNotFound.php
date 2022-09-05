<?php

namespace App\Presentation\Response\EndpointResponses\Delete;

use App\Domain\Exception\ProductNotFoundException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProductNotFound implements DeleteResponseStrategy
{
    public function matches(array $response = [], ?Exception $e = null): bool
    {
        return $e instanceof ProductNotFoundException;
    }

    public function response(array $response = [], ?Exception $e = null): JsonResponse
    {
        return new JsonResponse(['exception' => $e->getMessage()], Response::HTTP_NOT_FOUND);
    }
}