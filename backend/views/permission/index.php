<div class="layui-card">
    <div class="layui-card-header layuiadmin-card-header-auto">
        <div class="layui-btn-group">
            <a href="<?php echo \yii\helpers\Url::to(['create']) ?>" class="layui-btn layui-btn-sm">添加</a>
            <a id="searchBtn" class="layui-btn layui-btn-sm">搜索</a>
        </div>
        <div class="layui-form">
            <div class="layui-form-item">
                <div class="layui-input-inline">
                    <input type="text" id="name" placeholder="请输入权限标识" class="layui-input">
                </div>
                <div class="layui-input-inline">
                    <input type="text" id="description" placeholder="请输入权限名称" class="layui-input">
                </div>
            </div>
        </div>
    </div>
    <div class="layui-card-body">
        <table id="dataTable" lay-filter="dataTable"></table>
        <script type="text/html" id="toolbar">
            <a class="layui-btn layui-btn-sm" href="{{d.updateUrl}}" >编辑</a>
            <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="destroy">删除</a>
        </script>
    </div>
</div>

<?php
$url = yii\helpers\Url::to(['permission/index']);
$js = <<<JS
    layui.use(['layer', 'form', 'jquery', 'table', 'element','upload'],function() {
        var table = layui.table;
        var layer = layui.layer;
        var dataTable = table.render({
            elem: '#dataTable' 
            ,url: '{$url}'
            ,height: 480 
            ,page:true
            ,cols: [[ 
                {type: 'checkbox', fixed: 'left'}
                ,{field: 'id', title: 'ID', width: 80} 
                ,{field: 'name', title: '权限标识', width: 200}
                ,{field: 'description', title: '权限名称'} 
                ,{field: 'rule_name', title: '规则名称'} 
                ,{field: 'data', title: '规则数据',width:300} 
                ,{fixed: 'right', title:'操作',toolbar: '#toolbar', width:300}
            ]]
        });
        
        //监听工具条
        table.on('tool(dataTable)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
            var data = obj.data; //获得当前行数据
            var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
            var tr = obj.tr; //获得当前行 tr 的DOM对象
            if (layEvent=='destroy'){
                layer.confirm("确定删除吗？",function() {
                    $.get(data.destroyUrl,function(res) {
                        layer.msg(res.msg,{time:2000},function() {
                            if (res.code==0){
                                obj.del();
                            } 
                        })  
                    })
                }) 
            } 
        });
        
        //搜索
        $("#searchBtn").click(function() {
            var data = {
                name:$("#name").val(),
                description:$("#description").val(),
            }
            dataTable.reload({
                where:data,
                page:{curr:1}
            })
        })
        
    })
JS;
$this->registerJs($js);

?>
