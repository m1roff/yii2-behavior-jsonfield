<?php

namespace mirkhamidov\behaviors;

use Exception;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\JsonExpression;
use yii\helpers\Json;

/**
 * @property ActiveRecord $model
 */
class JsonFieldBehavior extends Behavior
{
    /**
     * @var string Field name supposed to contain array data
     */
    public $field;

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
     * Loads array field.
     *
     * @throws Exception
     *
     * @return $this
     */
    public function _loadArray(): JsonFieldBehavior
    {
        $value = $this->getModel()->getAttribute($this->field);

        try {
            if (is_string($value)) {
                $value = Json::decode($value);
            }
            if ($value instanceof JsonExpression) {
                $value = $value->getValue();
            }
        } catch (Exception $e) {
            $value = [];
        }
        $value = $value ?: [];
        $this->getModel()->setAttribute($this->field, $value);

        return $this;
    }

    /**
     * Sets array field data into format suitable for save.
     *
     * @throws Exception
     *
     * @return $this
     */
    public function _saveArray(): JsonFieldBehavior
    {
        $value = $this->getModel()->getAttribute($this->field);
        if (!($value instanceof JsonExpression) && !empty($value) && !is_string($value)) {
            if (!is_array($value) || !is_object($value)) {
                $value = (array) $value;
            }
            $value = new JsonExpression($value);
        }

        if (empty($value) && [] === $value) {
            $value = new JsonExpression($value);
        }

        if (empty($value)) {
            return $this;
        }

        $this->getModel()->setAttribute($this->field, $value);

        return $this;
    }

    /**
     * Returns model.
     *
     * @throws Exception
     *
     * @return ActiveRecord
     */
    private function getModel(): ActiveRecord
    {
        if (!$model = $this->owner) {
            throw new Exception('Model is not been initialized properly.');
        }
        if (!$model instanceof ActiveRecord) {
            throw new Exception(sprintf('Behavior must be applied to the ActiveRecord model class and it\'s iheritants, the unsupported class provided: `%s`', get_class($model)));
        }

        return $model;
    }
}
