<?php

namespace Igarevv\Micrame\Session;

interface SessionConnectionInterface
{
    public function start(): void;

    public function close(): void;

    public function regenerate(): void;
}