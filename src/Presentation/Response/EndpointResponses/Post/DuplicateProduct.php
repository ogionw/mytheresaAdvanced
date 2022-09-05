<?php

namespace App\Presentation\Response\EndpointResponses\Post;

use App\Domain\Exception\DuplicateSkuException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DuplicateProduct implements PostResponseStrategy
{
    public function matches(array $response = [], ?Exception $e = null): bool
    {
        return $e instanceof DuplicateSkuException;
    }

    public function response(array $response = [], ?Exception $e = null): JsonResponse
    {
        return new JsonResponse(['exception' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
    }
}