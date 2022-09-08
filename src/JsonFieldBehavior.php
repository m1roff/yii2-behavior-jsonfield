<?php

namespace m1roff\behaviors;

use m1roff\behaviors\Exception\ActiveRecordBehaviorOnlyException;
use m1roff\behaviors\Exception\MiddlewareException;
use m1roff\behaviors\Exception\ModelNotInitializedProperly;
use m1roff\behaviors\Middleware\DefaultLoadJsonExpressionMiddleware;
use m1roff\behaviors\Middleware\DefaultSaveJsonExpressionMiddleware;
use m1roff\behaviors\Middleware\MiddlewareHandler;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

/**
 * @property ActiveRecord $model
 */
class JsonFieldBehavior extends Behavior
{
    /**
     * Field name supposed to contain array data.
     */
    public string $field;

    /**
     * First go through default middlewares.
     */
    public bool $useDefaultLoadMiddlewares = true;

    public bool $useDefaultSaveMiddlewares = true;

    /**
     * Injectable middlewares.
     */
    public array $loadMiddlewares = [];

    public array $saveMiddlewares = [];

    private array $defaultLoadMiddlewares = [
        DefaultLoadJsonExpressionMiddleware::class,
    ];

    private array $defaultSaveMiddlewares = [
        DefaultSaveJsonExpressionMiddleware::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_INIT => '_loadArray',
            BaseActiveRecord::EVENT_AFTER_FIND => '_loadArray',
            BaseActiveRecord::EVENT_AFTER_INSERT => '_loadArray',
            BaseActiveRecord::EVENT_AFTER_UPDATE => '_loadArray',

            BaseActiveRecord::EVENT_BEFORE_INSERT => '_saveArray',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => '_saveArray',
        ];
    }

    /**
     * @throws ActiveRecordBehaviorOnlyException
     * @throws MiddlewareException
     * @throws ModelNotInitializedProperly
     */
    public function _loadArray(): JsonFieldBehavior
    {
        $value = $this->getModel()->getAttribute($this->field);

        $value = $this->createLoadMiddlewareHandler()->handle($value);

        $this->getModel()->setAttribute($this->field, $value);

        return $this;
    }

    /**
     * @throws ActiveRecordBehaviorOnlyException
     * @throws MiddlewareException
     * @throws ModelNotInitializedProperly
     */
    public function _saveArray(): JsonFieldBehavior
    {
        $value = $this->getModel()->getAttribute($this->field);

        $value = $this->createSaveMiddlewareHandler()->handle($value);

        $this->getModel()->setAttribute($this->field, $value);

        return $this;
    }

    private function createSaveMiddlewareHandler(): MiddlewareHandler
    {
        return new MiddlewareHandler(array_merge(
            $this->useDefaultSaveMiddlewares ? $this->defaultSaveMiddlewares : [],
            $this->saveMiddlewares,
        ));
    }

    private function createLoadMiddlewareHandler(): MiddlewareHandler
    {
        return new MiddlewareHandler(array_merge(
            $this->useDefaultLoadMiddlewares ? $this->defaultLoadMiddlewares : [],
            $this->loadMiddlewares,
        ));
    }

    /**
     * @throws ActiveRecordBehaviorOnlyException
     * @throws ModelNotInitializedProperly
     */
    private function getModel(): ActiveRecord
    {
        if (!$model = $this->owner) {
            throw new ModelNotInitializedProperly();
        }
        if (!$model instanceof ActiveRecord) {
            throw new ActiveRecordBehaviorOnlyException($model);
        }

        return $model;
    }
}
