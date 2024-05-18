<?php

namespace Igarevv\Micrame\Http\Response;

class JsonResponse extends Response
{
    public function __construct(array $content = [], int $status = 200, array $headers = [])
    {
        $toJson = json_encode($content, JSON_THROW_ON_ERROR);

        $this->setHeader('Content-Type', 'application/json');

        parent::__construct($toJson, $status, $headers);
    }

}