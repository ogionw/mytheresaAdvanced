<?php

namespace App\Presentation\Response\Generators;

use App\Presentation\Response\EndpointResponses\Common\ResponseStrategy;
use App\Presentation\Response\EndpointResponses\Put\PutResponseStrategy;
use App\Presentation\Response\ResponseGenerator;

class PutHttpResponseGenerator extends EndpointResponseGenerator implements ResponseGenerator
{
    protected function belongs(ResponseStrategy $responseStrategy): bool
    {
        return $responseStrategy instanceof PutResponseStrategy;
    }
}