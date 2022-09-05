<?php

namespace App\Presentation\Response\Generators;

use App\Presentation\Response\EndpointResponses\Common\ResponseStrategy;
use App\Presentation\Response\EndpointResponses\Delete\DeleteResponseStrategy;
use App\Presentation\Response\ResponseGenerator;

class DeleteHttpResponseGenerator extends EndpointResponseGenerator implements ResponseGenerator
{
    protected function belongs(ResponseStrategy $responseStrategy): bool
    {
        return $responseStrategy instanceof DeleteResponseStrategy;
    }
}