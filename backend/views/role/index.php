<div class="layui-card">
    <div class="layui-card-header layuiadmin-card-header-auto">
        <div class="layui-btn-group">
            <a href="<?php echo \yii\helpers\Url::to(['create']) ?>" class="layui-btn layui-btn-sm">添加</a>
        </div>
    </div>
    <div class="layui-card-body">
        <table id="dataTable" lay-filter="dataTable"></table>
        <script type="text/html" id="toolbar">
            <a class="layui-btn layui-btn-sm" href="{{d.updateUrl}}" >编辑</a>
            <a class="layui-btn layui-btn-sm" href="{{d.assignUrl}}" >子角色权限</a>
            <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="destroy">删除</a>
        </script>
        <script type="text/html" id="roles">
            {{# layui.each(d.roles,function(index,item){  }}
            <span class="layui-badge layui-bg-cyan">{{ item.description }}</span>
            {{# }) }}
        </script>
    </div>
</div>

<?php
$url = yii\helpers\Url::to(['role/index']);
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
                ,{field: 'name', title: '角色标识', width: 200}
                ,{field: 'description', title: '角色名称'} 
                ,{field: 'rule_name', title: '规则名称'} 
                ,{field: 'roles', title: '子角色',width:300,toolbar:'#roles'} 
                ,{fixed: 'right', title:'操作',toolbar: '#toolbar', width:230}
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
                username:$("#username").val(),
                email:$("#email").val(),
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
