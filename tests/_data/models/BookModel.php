<?php

declare(strict_types=1);

namespace app\tests\data\models;

use m1roff\behaviors\JsonFieldBehavior;
use yii\db\ActiveRecord;

/**
 * @property int id
 * @property array interests
 * @property array languages
 */
class BookModel extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'book';
    }

    public function behaviors(): array
    {
        return [
            'interestsJson' => [
                'class' => JsonFieldBehavior::class,
                'field' => 'interests',
            ],
            'languagesJson' => [
                'class' => JsonFieldBehavior::class,
                'field' => 'languages',
            ],
        ];
    }
}
