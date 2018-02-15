Behavior for JSON field
=

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
composer require mirkhamidov/yii2-behavior-jsonfield "dev-master"
```




Configure
-----
add behavior entry to you model

```php
/** @inheritdoc */
public function behaviors() {
    return ArrayHelper::merge(parent::behaviors(), [
        'interestsJson' => [
            'class' => JsonFieldBehavior::class,
            'field' => 'interests',
        ],
        'languagesJson' => [
            'class' => JsonFieldBehavior::class,
            'field' => 'languages',
        ],
    ]);
}
```