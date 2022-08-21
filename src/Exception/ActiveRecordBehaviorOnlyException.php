<?php

declare(strict_types=1);

namespace mirkhamidov\behaviors\Exception;

use yii\base\InvalidConfigException;

class ActiveRecordBehaviorOnlyException extends InvalidConfigException
{
    public function __construct(object $object)
    {
        parent::__construct(sprintf('Behavior must be applied to the ActiveRecord model class and it\'s inheritance, the unsupported class provided: `%s`', get_class($object)));
    }
}
