<?php

declare(strict_types=1);

namespace m1roff\behaviors\Middleware;

abstract class AbstractWithOwnerMiddleware implements MiddlewareInterface
{
    private ?object $owner = null;

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
}
