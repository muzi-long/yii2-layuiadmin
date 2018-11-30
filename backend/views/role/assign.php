<div class="layui-card">
    <div class="layui-card-header layuiadmin-card-header-auto">
        <h2>为角色分配子角色和权限</h2>
    </div>
    <div class="layui-card-body">
        <form class="layui-form" action="<?php echo \yii\helpers\Url::to(['assign','name'=>$role->name])?>" method="post">
            <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->getCsrfToken();?>">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">当前角色</label>
                <div class="layui-input-inline">
                    <input type="text" value="<?php echo $role->description.'('.$role->name.')'; ?>" disabled class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">子角色</label>
                <div class="layui-input-block">
                    <?php foreach ($roles as $key=>$role):?>
                    <input <?php echo in_array($key,array_keys($ownRoles))?'checked':'' ?> type="checkbox" name="role[]" value="<?php echo $role->name ?>" title="<?php echo $role->description ?>">
                    <?php endforeach;?>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">权限</label>
                <div class="layui-input-block">
                    <?php foreach ($permissions as $key=>$permission):?>
                        <input <?php echo in_array($key,array_keys($ownPermissions))?'checked':'' ?> type="checkbox" name="permission[]" value="<?php echo $permission->name ?>" title="<?php echo $permission->description ?>">
                    <?php endforeach;?>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label"></label>
                <div class="layui-input-block">
                    <button type="submit" lay-submit lay-filter="*" class="layui-btn layui-btn-sm">确定</button>
                    <a href="<?php echo \yii\helpers\Url::to(['index'])?>" class="layui-btn layui-btn-sm">返回</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php

$js = <<<JS
    layui.use(['layer', 'form', 'jquery', 'table', 'element','upload'],function() {
        var table = layui.table;
        var layer = layui.layer;
        
    })
JS;
$this->registerJs($js);

?>
