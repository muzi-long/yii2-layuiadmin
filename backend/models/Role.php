<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

class Role extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%auth_item}}';
    }

    public function rules()
    {
        return [
            ['name','required'],
            ['name','string','max'=>20],
            ['name','unique'],

            ['description','required'],
            ['description','string','max'=>100],

            ['rule_name','safe'],

            ['data','safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '角色标识',
            'description' => '角色名称',
            'rule_name' => '规则名称',
            'data' => '数据',
        ];
    }

    public function createRole()
    {
        if ($this->validate()){
            $auth = Yii::$app->authManager;
            $role = $auth->createRole($this->name);
            $role->description = $this->description;
            $role->ruleName = empty($this->rule_name)?null:$this->rule_name;
            $role->data = empty($this->data)?null:$this->data;
            if ($auth->add($role)){
                return true;
            }
        }
        return false;
    }

    public function updateRole()
    {
        if ($this->validate()){
            $auth = Yii::$app->authManager;
            $role = $auth->createRole($this->name);
            $role->description = $this->description;
            $role->ruleName = empty($this->rule_name)?null:$this->rule_name;
            $role->data = empty($this->data)?null:$this->data;
            if ($auth->update($this->getOldAttribute('name'),$role)){
                return true;
            }
        }
        return false;
    }



}