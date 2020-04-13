<?php

namespace Anso\Lib\Contract;

interface Authenticatable
{
    public function getLogin(): string;

    public function getPassword(): string;
}