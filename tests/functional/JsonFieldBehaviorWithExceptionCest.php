<?php

declare(strict_types=1);

namespace app\tests\functional;

use app\tests\_data\models\BookModelWithMiddlewaresButNotWellConfigured;
use FunctionalTester;
use m1roff\behaviors\Exception\ActiveRecordBehaviorOnlyException;
use m1roff\behaviors\Exception\MiddlewareException;
use m1roff\behaviors\Exception\ModelNotInitializedProperly;
use m1roff\behaviors\JsonFieldBehavior;
use yii\base\Component;
use yii\db\BaseActiveRecord;

class JsonFieldBehaviorWithExceptionCest
{
    public function testThrowMiddlewareException(FunctionalTester $I): void
    {
        $book = new BookModelWithMiddlewaresButNotWellConfigured();
        $book->interests = [
            'another-one-data',
        ];

        $I->expectThrowable(
            MiddlewareException::class,
            fn () => $book->save(),
        );
    }

    public function testOwnerNotProperlyInitialized(FunctionalTester $I): void
    {
        $behavior = new JsonFieldBehavior();

        $I->expectThrowable(
            ModelNotInitializedProperly::class,
            fn () => $behavior->_loadArray(),
        );
    }

    public function testActiveRecordBehaviorException(FunctionalTester $I): void
    {
        $dummyClass = new class extends Component {
            public function behaviors()
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
        };

        $I->expectThrowable(
            ActiveRecordBehaviorOnlyException::class,
            fn () => $dummyClass->trigger(BaseActiveRecord::EVENT_INIT),
        );
    }
}
