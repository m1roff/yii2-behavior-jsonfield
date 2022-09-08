<?php

declare(strict_types=1);

namespace app\tests\_data\middleware;

use m1roff\behaviors\Middleware\MiddlewareInterface;

class SecondMiddleware implements MiddlewareInterface
{
    public function handle(mixed $fieldValue, callable $next): mixed
    {
        $fieldValue[] = 'TWO more data from SecondMiddleware';

        return $next($fieldValue);
    }
}
