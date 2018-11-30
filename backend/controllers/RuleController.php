<?php
namespace backend\controllers;

use backend\models\Rule;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class RuleController extends BaseController
{

    public function actionIndex()
    {
        if (Yii::$app->request->isAjax){
            $rule = Rule::find();
            $count = $rule->count();
            $page = Yii::$app->request->get('page',1);
            $limit = Yii::$app->request->get('limit',10);
            $name = Yii::$app->request->get('name');
            if ($name){
                $rule->where(['like','name',$name]);
            }
            $data = $rule->offset(($page-1)*$limit)->limit($limit)->orderBy('created_at desc')->asArray()->all();
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
     * 添加规则
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Rule();
        if (Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post(),'')&&$model->createRule()){
                Yii::$app->session->setFlash('info','添加成功');
                return $this->redirect(['index']);
            }
        }
        return $this->render('create',['model'=>$model]);
    }

    /**
     * 更新规则
     * @param string $name
     * @return string|\yii\web\Response
     */
    public function actionUpdate(string $name)
    {
        $model = Rule::findOne(['name'=>$name]);
        if ($model==null){
            throw new NotFoundHttpException('规则不存在');
        }
        if (Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post(),'')&&$model->updateRule()){
                Yii::$app->session->setFlash('info','更新成功');
                return $this->redirect(['index']);
            }
        }
        return $this->render('update',['model'=>$model]);
    }

    /**
     * 删除规则
     * @param string $name
     * @return \yii\web\Response
     */
    public function actionDestroy(string $name)
    {

        $model = Rule::findOne(['name'=>$name]);
        if ($model==null){
            throw new NotFoundHttpException('规则不存在');
        }
        if ($model->delete()){
            return $this->asJson(['code'=>0,'msg'=>'删除成功']);
        }
        return $this->asJson(['code'=>1,'msg'=>'删除失败']);
    }

}