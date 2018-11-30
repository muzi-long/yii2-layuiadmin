<?php
namespace backend\controllers;

use backend\models\Permission;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class PermissionController extends BaseController
{

    /**
     * 权限列表
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        if (Yii::$app->request->isAjax){
            $permission = Permission::find()->where(['type'=>2]);
            $count = $permission->count();
            $page = Yii::$app->request->get('page',1);
            $limit = Yii::$app->request->get('limit',10);
            $name = Yii::$app->request->get('name');
            $description = Yii::$app->request->get('description');
            if ($name){
                $permission->where(['like','name',$name]);
            }
            if ($description){
                $permission->where(['like','description',$description]);
            }
            $data = $permission->offset(($page-1)*$limit)->limit($limit)->orderBy('created_at desc')->asArray()->all();
            foreach ($data as &$item){
                $item['updateUrl'] = Url::to(['update','name'=>$item['name']]);
                $item['destroyUrl'] = Url::to(['destroy','name'=>$item['name']]);
            }
            return $this->asJson([
                'code' => 0,
                'msg' => '请求成功',
                'count' => $count,
                'data' => $data
            ]);
        }
        return $this->render('index');
    }

    /**
     * 添加权限
     */
    public function actionCreate()
    {
        $model = new Permission();
        if (Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post(),'')&&$model->createPermission()){
                Yii::$app->session->setFlash('info','添加成功');
                return $this->redirect(['index']);
            }
        }
        $rules = Yii::$app->authManager->getRules();
        return $this->render('create',['model'=>$model,'rules'=>$rules]);
    }

    /**
     * 更新权限
     * @param string $name
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate(string $name)
    {

        $model = Permission::findOne(['name'=>$name,'type'=>2]);
        if ($model==null){
            throw new NotFoundHttpException('权限不存在');
        }
        if (Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post(),'')&&$model->updatePermission()){
                Yii::$app->session->setFlash('info','更新成功');
                return $this->redirect(['index']);
            }
        }
        $rules = Yii::$app->authManager->getRules();
        return $this->render('update',['model'=>$model,'rules'=>$rules]);
    }

    /**
     * 删除权限
     * @param string $name
     * @return \yii\web\Response
     */
    public function actionDestroy(string $name)
    {
        $auth = Yii::$app->authManager;
        $permission = $auth->getPermission($name);
        if ($permission === null){
            return $this->asJson(['code'=>1,'msg'=>'权限不存在']);
        }
        if ($auth->remove($permission)){
            return $this->asJson(['code'=>0,'msg'=>'删除成功']);
        }
        return $this->asJson(['code'=>1,'msg'=>'删除失败']);
    }

}