<?php

namespace App\Presentation\Response\EndpointResponses\Put;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Success implements PutResponseStrategy
{
    public function matches(array $response = [], ?Exception $e = null): bool
    {
        return is_null($e);
    }

    public function response(array $response = [], ?Exception $e = null): JsonResponse
    {
        return new JsonResponse(['message' => 'success'], Response::HTTP_OK);
    }
}