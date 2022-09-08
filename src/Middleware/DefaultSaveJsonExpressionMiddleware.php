<?php

declare(strict_types=1);

namespace m1roff\behaviors\Middleware;

use yii\db\JsonExpression;

class DefaultSaveJsonExpressionMiddleware implements MiddlewareInterface
{
    public function handle(mixed $fieldValue, callable $next): mixed
    {
        if (!($fieldValue instanceof JsonExpression) && !empty($fieldValue) && !is_string($fieldValue)) {
            if (!is_array($fieldValue) || !is_object($fieldValue)) {
                $fieldValue = (array) $fieldValue;
            }
            $fieldValue = new JsonExpression($fieldValue);
        }

        if (empty($fieldValue) && [] === $fieldValue) {
            $fieldValue = new JsonExpression($fieldValue);
        }

        if (empty($fieldValue)) {
            return $fieldValue;
        }

        return $next($fieldValue);
    }
}
