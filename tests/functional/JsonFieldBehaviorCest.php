<?php

declare(strict_types=1);

namespace app\tests\functional;

use app\tests\data\models\BookModel;
use FunctionalTester;

class JsonFieldBehaviorCest
{
    public function testCRUD(FunctionalTester $I): void
    {
        $book = new BookModel();
        $book->interests = [
            'interest-1',
            'interest-2',
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
        $bookId = $book->id;
        $book = null;

        $I->assertObjectMatchesSnapshot(BookModel::find()->asArray()->all());

        $loadedBook = BookModel::findOne($bookId);

        $I->assertObjectMatchesSnapshot($loadedBook);
    }
}
