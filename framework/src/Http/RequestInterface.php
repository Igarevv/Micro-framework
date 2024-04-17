<?php

namespace Igarevv\Micrame\Http;

interface RequestInterface
{

    public function getMethod(): string;

    public function getUri(): string;

    public function getPost(array $keys = []): array;

    public function getGet(array $keys = []): array;

    public function getFiles(?string $key = null): array;
}