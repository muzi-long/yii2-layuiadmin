<?php
namespace backend\models;

use yii\db\ActiveRecord;
use Yii;

class Rule extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%auth_rule}}';
    }

    public function rules()
    {
        return [
            ['name','required'],
            ['name','unique'],
            ['name','validateName'],
        ];
    }

    public function validateName($attribute,$param)
    {
        if (!$this->hasErrors()){
            $className = 'backend\\rules\\'.$this->name;
            if (!class_exists($className)){
                $this->addError($attribute,'规则名称不存在');
                return false;
            }
        }
    }

    public function attributeLabels()
    {
        return [
            'name' => '规则名称'
        ];
    }

    public function createRule()
    {
        if ($this->validate()){
            $auth = Yii::$app->authManager;
            $className = 'backend\\rules\\'.$this->name;
            $rule = new $className;
            if ($auth->add($rule)){
                return true;
            }
        }
    }

    public function updateRule()
    {
        if ($this->validate()){
            $auth = Yii::$app->authManager;
            $className = 'backend\\rules\\'.$this->name;
            $rule = new $className;
            if ($auth->update($this->getOldAttribute('name'),$rule)){
                return true;
            }
        }
    }

}
