<?php

declare(strict_types=1);

namespace mirkhamidov\behaviors\Middleware;

use yii\base\InvalidArgumentException;
use yii\db\JsonExpression;
use yii\helpers\Json;

class DefaultLoadJsonExpressionMiddleware implements MiddlewareInterface
{
    public function handle(mixed $fieldValue, callable $next): mixed
    {
        try {
            if (is_string($fieldValue)) {
                $fieldValue = Json::decode($fieldValue);
            }
            if ($fieldValue instanceof JsonExpression) {
                $fieldValue = $fieldValue->getValue();
            }
        } catch (InvalidArgumentException) {
            $fieldValue = [];
        }

        $fieldValue = $fieldValue ?: [];

        return $next($fieldValue);
    }
}
