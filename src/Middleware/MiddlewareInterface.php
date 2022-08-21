<?php

declare(strict_types=1);

namespace mirkhamidov\behaviors\Middleware;

use mirkhamidov\behaviors\Exception\MiddlewareException;

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
