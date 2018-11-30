<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>后台登录</title>
    <link href="/static/layui/css/layui.css" rel="stylesheet">
    <link href="/static/css/login.css" rel="stylesheet">
</head>
<body>

<div class="login-bg">
    <div class="layui-row">
        <div class="layui-col-lg4 layui-col-lg-offset4">
            <div class="login-box">
                <h1 class="login-title">
                    <center>小顶外呼</center>
                </h1>
                <form class="layui-form" action="<?php echo \yii\helpers\Url::to(['public/login']) ?>"  method="post">
                    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->getCsrfToken();?>">
                    <div class="layui-form-item field-loginform-username required">
                        <label class="layui-form-label" for="loginform-username">帐号</label>
                        <div class="layui-input-block">
                            <input type="text" name="LoginForm[username]" value="<?= $model->username; ?>" class="layui-input" lay-verify="required" placeholder="请输入帐号" aria-required="true">
                            <?php if ($model->getFirstError('username')):?>
                            <div style="color:#009688"><?php echo $model->getFirstError('username');?></div>
                            <?php endif;?>
                        </div>
                    </div>
                    <div class="layui-form-item field-loginform-password required">
                        <label class="layui-form-label" for="loginform-password">密码</label>
                        <div class="layui-input-block">
                            <input type="password" name="LoginForm[password]" class="layui-input" value="" lay-verify="required" placeholder="请输入密码">
                            <?php if ($model->getFirstError('password')):?>
                                <div style="color:#009688"><?php echo $model->getFirstError('password');?></div>
                            <?php endif;?>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label for="" class="layui-form-label"></label>
                        <div class="layui-input-block">
                            <button lay-submit lay-filter="*" type="submit" class="layui-btn">确定</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="/static/js/jquery.min.js"></script>
<script src="/static/layui/layui.all.js"></script>

</body>
</html>
