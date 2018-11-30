<?php
namespace backend\controllers;

use backend\models\Admin;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class AdminController extends BaseController
{
    /**
     * 用户列表
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        if (Yii::$app->request->isAjax){
            $admin = (new Admin())->find();
            $count = $admin->count();
            $page = Yii::$app->request->get('page',1);
            $limit = Yii::$app->request->get('limit',10);
            $username = Yii::$app->request->get('username');
            $email = Yii::$app->request->get('email');
            if ($username){
                $admin->where(['like','username',$username]);
            }
            if ($email){
                $admin->where(['like','email',$email]);
            }
            $data = $admin->offset(($page-1)*$limit)->limit($limit)->asArray()->all();
            foreach ($data as &$item){
                $item['updateUrl'] = Url::to(['update','id'=>$item['id']]);
                $item['destroyUrl'] = Url::to(['destroy','id'=>$item['id']]);
                $item['assignUrl'] = Url::to(['assign','id'=>$item['id']]);
                $item['roles'] = Yii::$app->authManager->getRolesByUser($item['id']);
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
     * 添加用户
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Admin();
        if (Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post(),'')&&$model->save()){
                Yii::$app->session->setFlash('info','添加成功');
                return $this->redirect(['index']);
            }
        }
        return $this->render('create',['model'=>$model]);
    }

    /**
     * 编辑用户
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate(int $id)
    {
        $model = Admin::findOne($id);
        if ($model === null){
            throw new NotFoundHttpException('数据不存在');
        }
        if (Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post(),'')&&$model->save()){
                Yii::$app->session->setFlash('info','更新成功');
                return $this->redirect(['index']);
            }
        }
        return $this->render('update',['model'=>$model]);
    }

    /**
     * 删除用户
     * @param int $id
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDestroy(int $id)
    {
        $model = Admin::findOne($id);
        if ($model === null){
            return $this->asJson(['code'=>1,'msg'=>'用户不存在']);
        }
        if ($model->delete()){
            return $this->asJson(['code'=>0,'msg'=>'删除成功']);
        }
        return $this->asJson(['code'=>1,'msg'=>'删除失败']);
    }

    /**
     * 分配角色权限
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionAssign(int $id)
    {
        $admin = Admin::findOne($id);
        if ($admin==null){
            throw new NotFoundHttpException('用户不存在');
        }
        $auth = Yii::$app->authManager;
        if (Yii::$app->request->isPost){
            $roleNames = Yii::$app->request->post('role',[]);
            $permissionNames = Yii::$app->request->post('permission',[]);
            $trans = Yii::$app->db->beginTransaction();
            try{
                //移除用户所有角色
                $auth->revokeAll($admin->id);

                //重新添加角色
                foreach ($roleNames as $name){
                    $role = $auth->getRole($name);
                    $auth->assign($role,$admin->id);
                }
                //重新添加权限
                foreach ($permissionNames as $name){
                    $permission = $auth->getPermission($name);
                    $auth->assign($permission,$admin->id);
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
            //用户已拥有角色
            $ownRoles = $auth->getRolesByUser($id);
            //所有权限
            $permissions = $auth->getPermissions();
            //用户已拥有的权限
            $ownPermissions = $auth->getPermissionsByUser($id);
            return $this->render('assign',['roles'=>$roles,'ownRoles'=>$ownRoles,'permissions'=>$permissions,'ownPermissions'=>$ownPermissions,'admin'=>$admin]);
        }

    }

}