<?php

namespace Anso\Lib\Exception;

use Anso\Framework\Contract\ApplicationException;
use Exception;

class AuthorizationException extends Exception implements ApplicationException
{
    protected $code = 403;
}