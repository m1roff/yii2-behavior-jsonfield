<?php

declare(strict_types=1);

namespace app\tests\_data\middleware;

use m1roff\behaviors\Exception\MiddlewareException;
use m1roff\behaviors\Middleware\DefaultLoadJsonExpressionMiddleware;
use m1roff\behaviors\Middleware\MiddlewareInterface;
use yii\db\JsonExpression;

class LoadMiddleware implements MiddlewareInterface
{
    public function handle(mixed $fieldValue, callable $next): mixed
    {
        if ($fieldValue instanceof JsonExpression) {
            throw new MiddlewareException(sprintf(
                'Before "%s" middleware "%s" should be utilized. Or custom one should be processed to parse JsonExpression.',
                self::class,
                DefaultLoadJsonExpressionMiddleware::class,
            ));
        }

        if (empty($fieldValue)) {
            return $next($fieldValue);
        }

        if (is_array($fieldValue)) {
            $fieldValue[] = 'Data Added in LoadMiddleware';
        }

        return $next($fieldValue);
    }
}
