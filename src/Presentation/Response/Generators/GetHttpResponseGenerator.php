<?php

namespace App\Presentation\Response\Generators;

use App\Presentation\Response\EndpointResponses\Common\ResponseStrategy;
use App\Presentation\Response\EndpointResponses\Get\GetResponseStrategy;
use App\Presentation\Response\ResponseGenerator;

class GetHttpResponseGenerator extends EndpointResponseGenerator implements ResponseGenerator
{
    protected function belongs(ResponseStrategy $responseStrategy): bool
    {
        return $responseStrategy instanceof GetResponseStrategy;
    }
}