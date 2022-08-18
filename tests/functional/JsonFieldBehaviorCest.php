<?php

declare(strict_types=1);

namespace app\tests\functional;

use app\tests\data\models\BookModel;
use Codeception\Test\Unit;
use FunctionalTester;
use mirkhamidov\behaviors\JsonFieldBehavior;
use Mockery;
use Spatie\Snapshots\MatchesSnapshots;
use UnitTester;
use Yii;
use yii\console\Application;
use yii\db\BaseActiveRecord;
use yii\db\ColumnSchema;
use yii\db\Connection;
use yii\db\Schema;
use yii\db\TableSchema;

/**
 * @property UnitTester $tester
 */
class JsonFieldBehaviorCest
{
    public function testCRUD(FunctionalTester $I): void
    {
        $book = new BookModel();
        $book->interests = [
            "interest-1",
            "interest-2",
        ];
        $book->languages = [
            'en' => 'en_EN',
            'de' => 'en_DE',
        ];
        $book->save();

        $I->assertObjectMatchesSnapshot(BookModel::find()->asArray()->all());

        $book->languages = [];
        $book->save();

        $I->assertObjectMatchesSnapshot(BookModel::find()->asArray()->all());

        $book->languages = ['another-type'];
        $book->save();

        $I->assertObjectMatchesSnapshot(BookModel::find()->asArray()->all());
    }
}
