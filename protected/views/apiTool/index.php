<form class="sui-form form-horizontal" method="post" action="">
    <input id="app_id" name="_form[id]" type="hidden" value="<?php echo $params_data['id'] ?>">
    <div class="sui-row-fluid">
        <div class="span4" id="request_list">

            <div class="control-group">
                <label for="url" class="control-label">锁定域名：</label>
                <div class="controls">
                    <select name="hosts_sel" id="hosts_sel">
                        <?php if(is_array($hostsData)){
                            foreach($hostsData as $k => $v){
                        ?>
                                <option value="<?php echo $v['hosts'] ?>" > <?php echo $v['hosts_name']; ?></option>
                        <?php }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="control-group">
                <label data-toggle="checkbox" class="control-label">是否锁定</label>
                    <div class="controls">
                        <input type="checkbox" id="hosts_cleck" name="hosts_cleck"  value="1">
                    </div>

            </div>

            <div class="control-group">
                <label for="dynamic_params" class="control-label">动态参数：</label>
                <div class="controls">
            <textarea id="dynamic_params"  placeholder="uid,1,用户id;
token,xxxxxxxx,验证字符串;"></textarea>
                </div>
            </div>




            <div class="control-group">
                <label for="url" class="control-label">域名：</label>
                <div class="controls">
                    <input id="hosts" name="hosts" type="text" placeholder="http://www.mywink.top/" value="">
                </div>
            </div>

            <div class="control-group">
                <label for="name" class="control-label">接口名称：</label>
                <div class="controls">
                    <input id="name" name="_form[name]" type="text" placeholder="测试接口" value="">
                </div>
            </div>

            <div class="control-group">
                <label for="type" class="control-label">类型：</label>
                <div class="controls">
                    <select name="_form[type]" id="type">
                        <?php if(is_array($type_data)){
                            foreach($type_data as $k => $v){
                                ?>
                                <option
                                    value="<?php echo $v['id'] ?>" ><?php echo $v['name']; ?></option>
                            <?php }
                        } ?>
                    </select>
                </div>
            </div>

            <div class="control-group">
                <label for="name" class="control-label">路径地址：</label>
                <div class="controls">
                    <input id="url" name="_form[url]" type="text" placeholder="apiTool/test" value="" type="text">
                </div>
            </div>

            <div class="control-group">
                <label for="params" class="control-label">参数：</label>
                <div class="controls">
            <textarea id="params" name="_form[params]" placeholder="name,测试名,备注;
id,1,备注"></textarea>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"></label>
                <div class="controls">
                    <input type="button" class="sui-btn btn-success" name="a" value="新增并调用" onclick="runApi(1)">
                    <input type="button" class="sui-btn btn-info" name="a" value="调用" onclick="runApi(0)">
                </div>
            </div>

        </div>

        <div class="span3" id="ret_data_list">

            <div class="control-group">
                <label  class="control-label">请求地址：</label><div class="controls" id="runUrl"></div>
            </div>
            <div class="control-group">
                <label  class="control-label">耗时(秒)：</label><div class="controls" id="runTime" color:'#f43838'></div>
            </div>
            <div class="control-group">
                <label  class="control-label">返回状态：</label><div class="controls" id="runStatus"></div>
            </div>
            <div class="control-group">
                <label for="retData" class="control-label">返回结果：</label>
                <div class="controls">
                    <textarea name="retData" id="retData" style="height:200px;width:100%;"><?php echo $params_data['retData'] ?></textarea>
                </div>
            </div>


        </div>

        <div class="span3" id="params_table_list">
            <table class="sui-table table-bordered">
                <thead>
                <tr>
                    <th>名称</th>
                    <th>参数</th>
                    <th>操作</th>
                </tr>

                </thead>
                <tbody>
                    <tr>
                        <td colspan="3"></td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>

</form>

<div id="api_list">

</div>


<script type="text/javascript">
    var ajax_page_type= 1; //防重复请求
    var ajax_page_params_type= 1; //防重复请求
    var ajax_run_type=1; //接口运行状态
    var ajax_del_type=1; //接口删除状态
    var api_list_page=1; //接口列表页码
    var params_list_page=1; //参数列表页码


    showApiList(api_list_page);

    //接口列表
    function showApiList(api_page){
        api_list_page=api_page;
        if(ajax_page_type==0){ msgLogin();return false; }else{ ajax_page_type=0; }

        $.post('<?php echo Yii::app()->request->baseUrl; ?>/apiTool/ajaxApiList', {api_page: api_page}, function (data) {
            if (data) {
                $('#api_list').html(data);
                ajax_page_type=1;
            } else {
                ajax_page_type=1;
            }
        })
    }

    //删除接口
    function apiDel(id) {
        if(ajax_del_type==0){ msgLogin();return false; }else{ ajax_del_type=0; }
        $.post('<?php echo Yii::app()->request->baseUrl; ?>/apiTool/ajaxApiDel', {id: id}, function (data) {
            if (data == 't') {
                showApiList(api_list_page);
                ajax_del_type=1;
            } else {
                alert('失败');
                ajax_del_type=1;
            }
        })
    }

    //api数据填充到各input
    function fillData(id){
        if(ajax_page_type==0){ msgLogin();return false; }else{ ajax_page_type=0; }
        $.get('<?php echo Yii::app()->request->baseUrl; ?>/apiTool/ajaxFillApiData', {id: id}, function (data) {
            if (data.status == 200) {
                $('#app_id').val(data.obj.id);
                $('#hosts').val(data.obj.hosts);
                $('#name').val(data.obj.name);
                $('#type').val(data.obj.type);
                $('#url').val(data.obj.url);
                $('#params').val(data.obj.params);
                ajax_page_type=1;
                //调用参数列表
                params_list_page=1;
                params_list(data.obj.id);
                
            } else {
                msgError();
                ajax_page_type=1;
            }
        })
    }

    //调用
    function runApi(save_type){


        var app_id=$('#app_id').val();
        var hosts_sel=$('#hosts_sel').val();
        var hosts_cleck=0
        if ($("#hosts_cleck").is(":checked")) {hosts_cleck=1;}else{hosts_cleck=0;}

        var hosts=$('#hosts').val();
        var name=$('#name').val();
        var type=$('#type').val();
        var url=$('#url').val();
        var dynamic_params=$('#dynamic_params').val();
        var params=$('#params').val();

        var json=JSON.stringify({'id':app_id,'hosts':hosts,'name':name,'type':type,'url':url,'params':params,'dynamic_params':dynamic_params})
        $.post('/apiTool/ajaxRequestApi',{type:save_type,json:json,hosts_sel:hosts_sel,hosts_cleck:hosts_cleck},function(data){
            if(ajax_run_type==0){ msgLogin();return false; }else{ ajax_run_type=0; }
            if(data){
                $('#runTime').html(data.runTime);
                $('#runStatus').html(data.runStatus);
                $('#runUrl').html(data.runUrl);
                if(data.runStatus==200){
                    $('#runStatus').attr('style','color:yellowgreen');
                }else if(data.runStatus==500){
                    $('#runStatus').attr('style','color:#f43838');
                }else{
                    $('#runStatus').attr('style','color:#ff7800');
                }
                $('#retData').val(data.retData);
                $('#app_id').val(data.id);
                params_list(data.id);
                if(save_type>0){
                    showApiList(1);
                }
                ajax_run_type=1;
            }else{
                msgError();
                ajax_run_type=1;
            }
        })
    }

    //数据参数列表
    function params_list(apiId){
        if(!apiId>0) {$('#params_table_list').html('');return false;}
        if(!ajax_page_params_type>0) { msgLogin();return false; }else{ ajax_page_params_type=0; }

        $.post('<?php echo Yii::app()->request->baseUrl; ?>/apiTool/AjaxParamsList',{id:apiId,params_list_page:params_list_page},function(data){
            if(data){
                $('#params_table_list').html(data);
                ajax_page_params_type=1;
            }else{
                ajax_page_params_type=1;
            }
        })
    }

    //参数分页
    function JsParamsPage(page){
        var app_id=$('#app_id').val();
        params_list_page=page;
        params_list(app_id);

    }
    //参数填充
    function FillParams(id){
        $('#params').val($('#ajax_list_params'+id).data('params'));
    }

    //删除参数
    function del_params(id) {
        var apiId=$('#app_id').val();
        $.post('<?php echo Yii::app()->request->baseUrl; ?>/apiTool/delParams', {apiId:apiId,id: id}, function (data) {
            if (data == 't') {
                params_list_page=1;
                params_list(apiId);
            } else {
                msgError();
            }
        })
    }

    function msgLogin(){
        alert('login...');
    }

    function msgError(){
        alert('服务器错误');
    }

</script>
