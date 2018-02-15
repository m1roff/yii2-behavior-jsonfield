<?php

namespace mirkhamidov\behaviors;


use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\Json;

class JsonFieldBehavior extends Behavior
{
    /**
     * @var string Field name supposed to contain array data
     */
    public $field;

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_INIT => '_loadArray',
            ActiveRecord::EVENT_AFTER_FIND => '_loadArray',
            ActiveRecord::EVENT_AFTER_INSERT => '_loadArray',
            ActiveRecord::EVENT_AFTER_UPDATE => '_loadArray',

            ActiveRecord::EVENT_BEFORE_INSERT => '_saveArray',
            ActiveRecord::EVENT_BEFORE_UPDATE => '_saveArray',
        ];
    }

    /**
     * Loads array field
     * @return $this
     * @throws \Exception
     */
    public function _loadArray()
    {
        $value = $this->getModel()->getAttribute($this->field);

        $value = Json::decode($value);
        $value = $value ?: [];
        $this->getModel()->setAttribute($this->field, $value);

        return $this;
    }

    /**
     * Sets array field data into format suitable for save
     *
     * @return $this
     * @throws \Exception
     */
    public function _saveArray()
    {
        $value = $this->getModel()->getAttribute($this->field);
        if (!empty($value) && !is_string($value)) {
            if (!is_array($value) || !is_object($value)) {
                $value = (array)$value;
            }
            $value = Json::encode($value);
        }

        if (empty($value)) {
            $value = '{}';
        }

        $this->getModel()->setAttribute($this->field, $value);

        return $this;
    }


    /**
     * Returns model
     *
     * @return ActiveRecord
     * @throws \Exception
     */
    private function getModel()
    {
        if (!$model = $this->owner) {
            throw new \Exception('Model is not been initialized properly.');
        }
        if (!$model instanceof ActiveRecord) {
            throw new \Exception(sprintf('Behavior must be applied to the ActiveRecord model class and it\'s iheritants, the unsupported class provided: `%s`', get_class($model)));
        }

        return $model;
    }
}