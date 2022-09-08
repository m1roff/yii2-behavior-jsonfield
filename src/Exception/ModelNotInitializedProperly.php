<?php

declare(strict_types=1);

namespace m1roff\behaviors\Exception;

use yii\base\InvalidConfigException;

class ModelNotInitializedProperly extends InvalidConfigException
{
    protected $message = 'Model has not been initialized properly.';
}
