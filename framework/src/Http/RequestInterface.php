<?php

namespace Igarevv\Micrame\Http;

interface RequestInterface
{

    public function getMethod(): string;

    public function getUri(): string;

}