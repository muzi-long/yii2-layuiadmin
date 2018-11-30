<div class="layui-card">
    <div class="layui-card-header layuiadmin-card-header-auto">
        <h2>更新用户</h2>
    </div>
    <div class="layui-card-body">
        <form class="layui-form" action="<?php echo \yii\helpers\Url::to(['update','id'=>$model->id])?>" method="post">
            <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->getCsrfToken();?>">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">用户名</label>
                <div class="layui-input-inline">
                    <input type="text" value="<?php echo $model->username; ?>" name="username" placeholder="请输入用户名" class="layui-input" maxlength="16" >
                </div>
                <?php if ($model->hasErrors('username')): ?>
                <div class="layui-form-mid layui-word-aux"><?php echo $model->getFirstError('username'); ?></div>
                <?php endif;?>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">邮箱</label>
                <div class="layui-input-inline">
                    <input type="email" value="<?php echo $model->email; ?>" name="email" placeholder="请输入邮箱" class="layui-input" >
                </div>
                <?php if ($model->hasErrors('email')): ?>
                    <div class="layui-form-mid layui-word-aux"><?php echo $model->getFirstError('email'); ?></div>
                <?php endif;?>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">密码</label>
                <div class="layui-input-inline">
                    <input type="password" name="password_hash" placeholder="请输入密码" class="layui-input" maxlength="16" >
                </div>
                <?php if ($model->hasErrors('password_hash')): ?>
                    <div class="layui-form-mid layui-word-aux"><?php echo $model->getFirstError('password_hash'); ?></div>
                <?php endif;?>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">确认密码</label>
                <div class="layui-input-inline">
                    <input type="password" name="re_password_hash" placeholder="确认密码" class="layui-input" maxlength="16" >
                </div>
                <?php if ($model->hasErrors('re_password_hash')): ?>
                    <div class="layui-form-mid layui-word-aux"><?php echo $model->getFirstError('re_password_hash'); ?></div>
                <?php endif;?>
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
