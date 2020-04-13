<?php

namespace Anso\Lib\Contract;

interface Logger
{
    public function log(string $data): void;
}