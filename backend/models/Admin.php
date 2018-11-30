<?php

namespace backend\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%admin}}".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class Admin extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $re_password_hash;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admin}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT=>'password_hash',
                ],
                'value' => function($event){
                    return Yii::$app->security->generatePasswordHash($this->password_hash);
                }
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT=>'auth_key',
                ],
                'value' => function($event){
                    return Yii::$app->security->generateRandomString();
                }
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT=>'password_reset_token',
                ],
                'value' => function($event){
                    return Yii::$app->security->generateRandomString().'_'.time();
                }
            ]
        ];
    }

    public function rules()
    {
        return [
            [['username','email','password_hash','re_password_hash'],'required'],
            [['username'],'string','min'=>6,'max'=>16],
            ['username','unique'],
            ['email','unique','message'=>'邮箱已存在'],
            ['email','email'],
            [['password_hash','re_password_hash'],'string','min'=>6,'max'=>16],
            ['re_password_hash','compare','compareAttribute'=>'password_hash'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password_hash' => '密码',
            'email' => '邮箱',
            're_password_hash' => '确认密码'
        ];
    }


    public static function findIdentity($id)
    {
        // TODO: Implement findIdentity() method.
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public function getId()
    {
        // TODO: Implement getId() method.
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
        return $this->getAuthKey() === $authKey;
    }
}
