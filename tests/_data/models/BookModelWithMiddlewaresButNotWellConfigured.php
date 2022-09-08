<?php

declare(strict_types=1);

namespace app\tests\_data\models;

use app\tests\_data\middleware\LoadMiddleware;
use app\tests\data\models\BookModel;
use m1roff\behaviors\JsonFieldBehavior;
use yii\helpers\ArrayHelper;

class BookModelWithMiddlewaresButNotWellConfigured extends BookModel
{
    public function behaviors(): array
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'interestsJson' => [
                'class' => JsonFieldBehavior::class,
                'field' => 'interests',
                'useDefaultLoadMiddlewares' => false,
                'loadMiddlewares' => [
                    LoadMiddleware::class,
                ],
            ],
        ]);
    }
}
