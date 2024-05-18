<?php

namespace App\Http\Forms;

abstract class AbstractForm
{

    protected \stdClass $data;

    protected array $errors = [];

    protected function data(array $inputData): \stdClass
    {
        $this->data = (object) $inputData;

        return $this->data;
    }

    protected function less(string $string, int $less): bool
    {
        return strlen($string) < $less;
    }

    protected function more(string $string, int $more): bool
    {
        return strlen($string) > $more;
    }

    abstract public function errors(): array;

}