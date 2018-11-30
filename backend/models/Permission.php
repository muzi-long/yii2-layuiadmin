<?php
namespace backend\models;

use yii\db\ActiveRecord;
use Yii;

class Permission extends ActiveRecord
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
            'name' => '权限标识',
            'description' => '权限名称',
            'rule_name' => '规则名称',
            'data' => '数据',
        ];
    }

    public function createPermission()
    {
        if ($this->validate()){
            $auth = Yii::$app->authManager;
            $permission = $auth->createPermission($this->name);
            $permission->description = $this->description;
            $permission->ruleName = empty($this->rule_name)?null:$this->rule_name;
            $permission->data = empty($this->data)?null:$this->data;
            if ($auth->add($permission)){
                return true;
            }
        }
        return false;
    }

    public function updatePermission()
    {
        if ($this->validate()){
            $auth = Yii::$app->authManager;
            $permission = $auth->createPermission($this->name);
            $permission->description = $this->description;
            $permission->ruleName = empty($this->rule_name)?null:$this->rule_name;
            $permission->data = empty($this->data)?null:$this->data;
            if ($auth->update($this->getOldAttribute('name'),$permission)){
                return true;
            }
        }
        return false;
    }

}