<?php

namespace App\Application\Presenter;

use stdClass;

interface Presenter
{
    public function toBase(): stdClass;

    public function toArray(): array;
}