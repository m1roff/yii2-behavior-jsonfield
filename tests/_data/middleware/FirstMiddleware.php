<?php

declare(strict_types=1);

namespace app\tests\_data\middleware;

use mirkhamidov\behaviors\Middleware\MiddlewareInterface;

class FirstMiddleware implements MiddlewareInterface
{
    public string $message = '';

    public function handle(mixed $fieldValue, callable $next): mixed
    {
        $message = 'one more data from FirstMiddleware';
        if (!empty($this->message)) {
            $message = $this->message;
        }

        $fieldValue[] = $message;

        return $next($fieldValue);
    }
}
