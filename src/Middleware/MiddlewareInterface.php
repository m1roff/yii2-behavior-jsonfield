<?php

declare(strict_types=1);

namespace m1roff\behaviors\Middleware;

use m1roff\behaviors\Exception\MiddlewareException;

interface MiddlewareInterface
{
    /**
     * @param mixed $fieldValue
     * @param callable(mixed $fieldValue): mixed $next
     *
     * @throws MiddlewareException
     */
    public function handle(mixed $fieldValue, callable $next): mixed;
}
