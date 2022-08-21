<?php

declare(strict_types=1);

namespace mirkhamidov\behaviors\Middleware;

use mirkhamidov\behaviors\Exception\MiddlewareException;
use Yii;

final class MiddlewareHandler
{
    /** @var MiddlewareInterface[] */
    private array $middlewares = [];

    public function __construct(array $middlewares)
    {
        foreach ($middlewares as $middleware) {
            $this->middlewares[] = Yii::createObject($middleware);
        }
    }

    /**
     * @param mixed $fieldValue
     *
     * @throws MiddlewareException
     */
    public function handle($fieldValue)
    {
        $middleware = array_shift($this->middlewares);

        if (null !== $middleware) {
            return $middleware->handle($fieldValue, [$this, 'handle']);
        }

        return $fieldValue;
    }
}
