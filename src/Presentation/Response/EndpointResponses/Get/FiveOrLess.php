<?php

namespace App\Presentation\Response\EndpointResponses\Get;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class FiveOrLess implements GetResponseStrategy
{
    public function matches(array $response = [], ?Exception $e = null): bool
    {
        return is_null($e);
    }

    public function response(array $response = [], ?Exception $e = null): JsonResponse
    {
        return new JsonResponse($response, Response::HTTP_OK);
    }
}