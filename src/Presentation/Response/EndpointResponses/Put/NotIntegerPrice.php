<?php

namespace App\Presentation\Response\EndpointResponses\Put;

use App\Presentation\Exception\NotIntegerPriceException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class NotIntegerPrice implements PutResponseStrategy
{
    public function matches(array $response = [], ?Exception $e = null): bool
    {
        return $e instanceof NotIntegerPriceException;
    }

    public function response(array $response = [], ?Exception $e = null): JsonResponse
    {
        return new JsonResponse(['exception' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
    }
}