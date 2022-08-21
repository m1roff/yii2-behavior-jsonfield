<?php

declare(strict_types=1);

namespace app\tests\_data\middleware;

use mirkhamidov\behaviors\Exception\MiddlewareException;
use mirkhamidov\behaviors\Middleware\DefaultLoadJsonExpressionMiddleware;
use mirkhamidov\behaviors\Middleware\MiddlewareInterface;
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
