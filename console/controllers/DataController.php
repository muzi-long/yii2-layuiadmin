<?php
namespace console\controllers;

use backend\models\Admin;
use yii\console\Controller;



class DataController extends Controller
{
    public function actionInit()
    {
        $auth = \Yii::$app->authManager;
        $db = \Yii::$app->db;
        $connection = $db->createCommand();
        $transaction = $db->beginTransaction();
        try{
            echo "============用户填充【开始】============\n";
            $admin = new Admin();
            $admin->username = 'root';
            $admin->password_hash = '123456';
            $admin->email = 'root@qq.com';
            $admin->save(false);
            echo "填充用户名：{$admin['username']}\n";
            echo "============用户填充【完成】============\n";
            echo "\n";

            echo "============权限填充【开始】============\n";
            $controllerPath = 'backend\\controllers\\';
            foreach (scandir($controllerPath) as $fileName){
                if ($fileName!='.' && $fileName!='..'){
                    $content = file_get_contents($controllerPath.$fileName);
                    //取控制器名称
                    if (preg_match('/class (\w+)Controller/',$content,$mach1)){
                        $controller = strtolower($mach1[1]);
                        $connection->insert('{{%auth_item}}',[
                            'name' => $controller.'/*',
                            'type' => 2,
                            'description' => $controller.'/*',
                            'created_at' => time(),
                            'updated_at' => time(),
                        ])->execute();
                        echo "填充权限".$controller."/*\n";
                        //取actions名称
                        if (preg_match_all('/public function action(\w+)\(/',$content,$mach2)){
                            foreach ($mach2[1] as $action){
                                $connection->insert('{{%auth_item}}',[
                                    'name' => $controller.'/'.strtolower($action),
                                    'type' => 2,
                                    'description' => $controller.'/'.strtolower($action),
                                    'created_at' => time(),
                                    'updated_at' => time(),
                                ])->execute();
                                echo "--填充权限".$controller.'/'.strtolower($action)."\n";
                            }
                        }
                    }
                }
            }
            echo "============权限填充【完成】============\n";
            echo "\n";

            echo "============角色填充【开始】============\n";
            $datas = [
                ['name' => 'root','description'=>'超级管理员'],
                ['name' => 'admin','description'=>'管理员'],
                ['name' => 'tester','description'=>'测试人员'],
                ['name' => 'editor','description'=>'编辑'],
            ];
            foreach ($datas as $data){
                $role = $auth->createRole($data['name']);
                $role->description = $data['description'];
                $auth->add($role);
                echo "填充角色{$data['name']}-{$data['description']}\n";
            }
            echo "============角色填充【完成】============\n";
            echo "\n";

            echo "============授权填充【开始】============\n";
            $role = $auth->getRole('root');
            $permissions = $auth->getPermissions();
            foreach ($permissions as $key=>$item){
                $auth->assign($item,$admin->id);
                echo "为用户：".$admin->username."授权".$item->description."权限\n";
                $auth->addChild($role,$item);
                echo "为角色：".$role->description."授权".$item->description."权限\n";
            }
            $roles = $auth->getRoles();
            foreach ($roles as $key=>$item){
                $auth->assign($item,$admin->id);
                echo "为用户：".$admin->username."授权".$item->description."角色\n";
            }
            echo "============授权填充【完成】============\n";
            echo "\n";

            $transaction->commit();

        }catch (\Exception $exception){
            $transaction->rollBack();
            echo "????????????运行出错????????????\n";
            echo $exception->getMessage();
            echo "\n";
        }
    }
}