<?php
namespace backend\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;

class BaseController extends Controller
{

    /*public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['*'],
                //'except' => ['login','logout'],
                'rules' => [
                    [
                        'allow' => false,
                        'actions' => ['*'],
                        'roles' => ['?']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['@']
                    ]
                ],
            ],
        ];
    }*/

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)){
            return false;
        }
        $controller = $action->controller->id;
        $action = $action->id;

        if ($controller=='public'){
            return true;
        }

        if (\Yii::$app->user->can($controller.'/*')){
            return true;
        }
        if (\Yii::$app->user->can($controller.'/'.$action)){
            return true;
        }
        if (\Yii::$app->request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;
            \Yii::$app->response->data = ['code' => 1, 'msg' => '未授权访问'];
            return false;
        }
        throw new UnauthorizedHttpException('未授权访问');
        return true;
    }

}