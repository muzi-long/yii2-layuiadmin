<div class="layui-card">
    <div class="layui-card-header layuiadmin-card-header-auto">
        <h2>添加规则</h2>
    </div>
    <div class="layui-card-body">
        <form class="layui-form" action="<?php echo \yii\helpers\Url::to(['create'])?>" method="post">
            <?php echo $this->render('_form',['model'=>$model]); ?>
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
