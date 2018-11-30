<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <?= $content ?>
    </div>
</div>
<?php $this->endBody() ?>
<script>
    layui.use(['layer'],function () {
        var layer = layui.layer;
        <?php if (\Yii::$app->session->getFlash('info')):?>
        layer.msg("<?php echo \Yii::$app->session->getFlash('info') ?>");
        <?php endif;?>
    })
</script>
</body>
</html>
<?php $this->endPage() ?>
