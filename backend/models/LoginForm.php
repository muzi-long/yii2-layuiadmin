<?php
namespace backend\models;

use yii\base\Model;
use yii\helpers\VarDumper;
use Yii;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    public function rules()
    {
        return [
            [['username','password'],'required'],
            ['rememberMe', 'boolean']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码'
        ];
    }

    public function login()
    {
        if ($this->validate()) {
            //验证密码
            $user = Admin::findOne(['username'=>$this->username]);
            if (!$user || !Yii::$app->security->validatePassword($this->password,$user->password_hash)){
                $this->addError('password','帐号或密码错误');
                return false;
            }
            return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    }



}