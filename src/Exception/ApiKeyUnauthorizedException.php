<?php
declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ApiKeyUnauthorizedException extends UnauthorizedHttpException
{
    public function __construct(string $message = '')
    {
        parent::__construct('Invalid Api Key' ,$message);
    }
}
