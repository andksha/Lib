<?php

namespace Anso\Lib\Exception;

use Anso\Framework\Base\Contract\ApplicationException;
use Exception;

class AuthorizationException extends Exception implements ApplicationException
{
    protected $code = 403;
}