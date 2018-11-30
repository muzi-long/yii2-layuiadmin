<input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->getCsrfToken();?>">
<div class="layui-form-item">
    <label for="" class="layui-form-label">标识</label>
    <div class="layui-input-inline">
        <input type="text" value="<?php echo $model->getAttribute('name'); ?>" name="name" placeholder="请输入标识,如admin/create" class="layui-input" maxlength="20" >
    </div>
    <?php if ($model->hasErrors('name')): ?>
        <div class="layui-form-mid layui-word-aux"><?php echo $model->getFirstError('name'); ?></div>
    <?php endif;?>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">名称</label>
    <div class="layui-input-inline">
        <input type="text" value="<?php echo $model->getAttribute('description'); ?>" name="description" placeholder="请输入名称,如添加用户" class="layui-input" maxlength="100" >
    </div>
    <?php if ($model->hasErrors('description')): ?>
        <div class="layui-form-mid layui-word-aux"><?php echo $model->getFirstError('description'); ?></div>
    <?php endif;?>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">规则名称</label>
    <div class="layui-input-inline">
        <select name="rule_name" >
            <option value="">请选择</option>
            <?php foreach ($rules as $key=>$rule):?>
                <option <?php echo $key==$model->getAttribute('rule_name')?'selected':'' ?> value="<?php echo $key ?>"><?php echo $key ?></option>
            <?php endforeach;?>
        </select>
    </div>
    <?php if ($model->hasErrors('rule_name')): ?>
        <div class="layui-form-mid layui-word-aux"><?php echo $model->getFirstError('rule_name'); ?></div>
    <?php endif;?>

</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label">规则数据</label>
    <div class="layui-input-inline">
        <textarea class="layui-textarea" name="data" ><?php echo $model->getAttribute('data'); ?></textarea>
    </div>
    <div class="layui-form-mid layui-word-aux">非必填</div>
</div>
<div class="layui-form-item">
    <label for="" class="layui-form-label"></label>
    <div class="layui-input-block">
        <button type="submit" lay-submit lay-filter="*" class="layui-btn layui-btn-sm">确定</button>
        <a href="<?php echo \yii\helpers\Url::to(['index'])?>" class="layui-btn layui-btn-sm">返回</a>
    </div>
</div>