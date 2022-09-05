<?php

namespace App\Presentation\Response;

use App\Presentation\Response\Generators\DropoffHttpResponseGenerator;
use App\Presentation\Response\Generators\DeleteHttpResponseGenerator;
use App\Presentation\Response\Generators\PutHttpResponseGenerator;
use App\Presentation\Response\Generators\PostHttpResponseGenerator;
use App\Presentation\Response\Generators\GetHttpResponseGenerator;
use Symfony\Component\HttpFoundation\Request;

class HttpResponseGeneratorFactory
{

    public function __construct(private iterable $responses){}

    public function create(string $httpMethod): ResponseGenerator
    {
        return match ($httpMethod) {
            Request::METHOD_GET => new GetHttpResponseGenerator($this->responses),
            Request::METHOD_POST => new PostHttpResponseGenerator($this->responses),
            Request::METHOD_PUT => new PutHttpResponseGenerator($this->responses),
            Request::METHOD_DELETE => new DeleteHttpResponseGenerator($this->responses)
        };
    }
}