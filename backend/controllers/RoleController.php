<?php
namespace backend\controllers;

use backend\models\Role;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class RoleController extends BaseController
{
    /**
     * 角色列表
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        if (Yii::$app->request->isAjax){
            $role = Role::find()->where(['type'=>1]);
            $count = $role->count();
            $page = Yii::$app->request->get('page',1);
            $limit = Yii::$app->request->get('limit',10);
            $data = $role->offset(($page-1)*$limit)->limit($limit)->orderBy('created_at desc')->asArray()->all();
            foreach ($data as &$item){
                $item['updateUrl'] = Url::to(['update','name'=>$item['name']]);
                $item['destroyUrl'] = Url::to(['destroy','name'=>$item['name']]);
                $item['assignUrl'] = Url::to(['assign','name'=>$item['name']]);
                $childRole = Yii::$app->authManager->getChildRoles($item['name']);
                //移除自身
                unset($childRole[$item['name']]);
                $item['roles'] = $childRole;
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
     * 添加角色
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Role();
        if (Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post(),'')&&$model->createRole()){
                Yii::$app->session->setFlash('info','添加成功');
                return $this->redirect(['index']);
            }
        }
        $rules = Yii::$app->authManager->getRules();
        return $this->render('create',['model'=>$model,'rules'=>$rules]);
    }

    /**
     * 编辑角色
     * @param string $name
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate(string $name)
    {
        $model = Role::findOne(['name'=>$name,'type'=>1]);
        if ($model==null){
            throw new NotFoundHttpException('角色不存在');
        }
        if (Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post(),'')&&$model->updateRole()){
                Yii::$app->session->setFlash('info','更新成功');
                return $this->redirect(['index']);
            }
        }
        $rules = Yii::$app->authManager->getRules();
        return $this->render('update',['model'=>$model,'rules'=>$rules]);
    }

    /**
     * 删除角色
     * @param string $name
     * @return \yii\web\Response
     */
    public function actionDestroy(string $name)
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($name);
        if ($role === null){
            return $this->asJson(['code'=>1,'msg'=>'角色不存在']);
        }
        if ($auth->remove($role)){
            return $this->asJson(['code'=>0,'msg'=>'删除成功']);
        }
        return $this->asJson(['code'=>1,'msg'=>'删除失败']);
    }

    public function actionAssign(string $name)
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($name);
        if ($role==null){
            throw new NotFoundHttpException('角色不存在');
        }
        $auth = Yii::$app->authManager;
        if (Yii::$app->request->isPost){
            $roleNames = Yii::$app->request->post('role',[]);
            $permissionNames = Yii::$app->request->post('permission',[]);
            $trans = Yii::$app->db->beginTransaction();
            try{
                //移除角色的所有子角色和权限
                $auth->removeChildren($role);
                //重新添加角色
                foreach ($roleNames as $name){
                    $child = $auth->getRole($name);
                    $auth->addChild($role,$child);
                }
                //重新添加权限
                foreach ($permissionNames as $name){
                    $child = $auth->getPermission($name);
                    $auth->addChild($role,$child);
                }
                $trans->commit();
                Yii::$app->session->setFlash('info','更新成功');
                return $this->render('index');
            }catch (\Exception $exception){
                $trans->rollBack();
                Yii::$app->session->setFlash('info','更新失败');
                return $this->refresh();
            }

        }else{

            //所有角色
            $roles = $auth->getRoles();
            foreach ($roles as $key=>$item){
                if (!$auth->canAddChild($role,$item)){
                    unset($roles[$key]);
                }
            }
            //角色已拥有的子角色
            $ownRoles = $auth->getChildRoles($name);
            //所有权限
            $permissions = $auth->getPermissions();
            foreach ($permissions as $key=>&$item){
                if (!$auth->canAddChild($role,$item)){
                    unset($permissions[$key]);
                }
            }
            //角色已拥有的权限
            $ownPermissions = $auth->getPermissionsByRole($name);
            return $this->render('assign',['roles'=>$roles,'ownRoles'=>$ownRoles,'permissions'=>$permissions,'ownPermissions'=>$ownPermissions,'role'=>$role]);
        }
    }

}