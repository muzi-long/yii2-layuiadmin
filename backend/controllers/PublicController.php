<?php
namespace backend\controllers;

use backend\models\LoginForm;
use Yii;

class PublicController extends BaseController
{

    public function actionIframe()
    {
        $this->layout = false;
        return $this->render('iframe');
    }

    public function actionIndex1()
    {
        return $this->render('index1');
    }

    public function actionIndex2()
    {
        return $this->render('index2');
    }

    public function actionIndex3()
    {
        return $this->render('index3');
    }

    /**
     * 登录
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest){
            return $this->goHome();
        }
        $model = new LoginForm();
        if (Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post())&&$model->login()){
                return $this->goHome();
            }
        }
        $this->layout = false;
        return $this->render('login',['model'=>$model]);
    }

    /**
     * 退出
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect(['public/login']);
    }

    public function actionError()
    {
        $message = Yii::$app->errorHandler->exception->getMessage();
        return $this->render('error', ['message'=>$message]);
    }

}