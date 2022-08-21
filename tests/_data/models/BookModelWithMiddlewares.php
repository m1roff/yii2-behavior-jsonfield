<?php

declare(strict_types=1);

namespace app\tests\_data\models;

use app\tests\_data\middleware\FirstMiddleware;
use app\tests\_data\middleware\LoadMiddleware;
use app\tests\_data\middleware\SecondMiddleware;
use app\tests\data\models\BookModel;
use mirkhamidov\behaviors\JsonFieldBehavior;
use mirkhamidov\behaviors\Middleware\DefaultSaveJsonExpressionMiddleware;
use yii\helpers\ArrayHelper;

class BookModelWithMiddlewares extends BookModel
{
    public function behaviors(): array
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'interestsJson' => [
                'class' => JsonFieldBehavior::class,
                'field' => 'interests',
                'useDefaultSaveMiddlewares' => false,
                // 'useDefaultLoadMiddlewares' => false,
                'saveMiddlewares' => [
                    SecondMiddleware::class,
                    FirstMiddleware::class,
                    [
                        'class' => FirstMiddleware::class,
                        'message' => 'Manual text from BookModelWithMiddlewares',
                    ],
                    DefaultSaveJsonExpressionMiddleware::class,
                ],
                'loadMiddlewares' => [
                    LoadMiddleware::class,
                ],
            ],
        ]);
    }
}
