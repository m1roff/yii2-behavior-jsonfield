<?php

namespace mirkhamidov\behaviors;

use mirkhamidov\behaviors\Exception\ActiveRecordBehaviorOnlyException;
use mirkhamidov\behaviors\Exception\MiddlewareException;
use mirkhamidov\behaviors\Exception\ModelNotInitializedProperly;
use mirkhamidov\behaviors\Middleware\DefaultLoadJsonExpressionMiddleware;
use mirkhamidov\behaviors\Middleware\DefaultSaveJsonExpressionMiddleware;
use mirkhamidov\behaviors\Middleware\MiddlewareHandler;
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
