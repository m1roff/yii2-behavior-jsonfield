<?php

declare(strict_types=1);

namespace m1roff\behaviors\Middleware;

use m1roff\behaviors\Exception\MiddlewareException;
use Yii;

final class MiddlewaresHandler
{
    /** @var MiddlewareInterface[] */
    private array $middlewares = [];

    private ?object $owner = null;

    public function __construct(array $middlewares)
    {
        foreach ($middlewares as $middleware) {
            $this->middlewares[] = Yii::createObject($middleware);
        }
    }

    public function getOwner(): ?object
    {
        return $this->owner;
    }

    public function withOwner(object $owner): self
    {
        $clone = clone $this;
        $clone->owner = $owner;

        return $clone;
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
            if ($middleware instanceof AbstractWithOwnerMiddleware) {
                $middleware = $middleware->withOwner($this->owner);
            }

            return $middleware->handle($fieldValue, [$this, 'handle']);
        }

        return $fieldValue;
    }
}
