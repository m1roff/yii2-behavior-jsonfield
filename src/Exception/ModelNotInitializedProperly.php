<?php

declare(strict_types=1);

namespace mirkhamidov\behaviors\Exception;

use yii\base\InvalidConfigException;

class ModelNotInitializedProperly extends InvalidConfigException
{
    protected $message = 'Model has not been initialized properly.';
}
