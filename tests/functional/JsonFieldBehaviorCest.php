<?php

declare(strict_types=1);

namespace app\tests\functional;

use app\tests\_data\models\BookModelWithMiddlewares;
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

    public function testWithMiddlewares(FunctionalTester $I): void
    {
        $book = new BookModelWithMiddlewares();
        $book->interests = [
            'interest-middleware-1',
        ];
        $book->save();

        $I->assertObjectMatchesSnapshot(BookModel::find()->asArray()->all());

        $bookId = $book->id;
        $book = null;

        $loadedBook = BookModelWithMiddlewares::findOne($bookId);
        $I->assertNotNull($loadedBook, 'Book not loaded by Id');

        $I->assertObjectMatchesSnapshot($loadedBook->interests);
    }
}
