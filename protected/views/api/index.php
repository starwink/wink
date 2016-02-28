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
                                <option value="<?php echo $v['hosts'] ?>" <?php if($v['hosts'] == $params_data['hosts']){ ?> selected="selected" <?php } ?>><?php echo $v['hosts_name']; ?></option>
                        <?php }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="control-group">
                <label data-toggle="checkbox" class="control-label">是否锁定</label>
                    <div class="controls">
                        <input type="checkbox" name="hosts_cleck" <?php if($params_data['hosts_cleck']==1){ ?> checked="checked" <?php } ?> value="1">
                    </div>

            </div>




            <div class="control-group">
                <label for="url" class="control-label">域名：</label>
                <div class="controls">
                    <input id="hosts" name="hosts" type="text" placeholder="http://www.mywink.top/" value="<?php echo $params_data['hosts'] ?>">
                </div>
            </div>

            <div class="control-group">
                <label for="name" class="control-label">接口名称：</label>
                <div class="controls">
                    <input id="name" name="_form[name]" type="text" placeholder="测试接口" value="<?php echo $params_data['name'] ?>">
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
                                    value="<?php echo $v['id'] ?>" <?php if($v['id'] == $params_data['type']){ ?> selected="selected" <?php } ?>><?php echo $v['name']; ?></option>
                            <?php }
                        } ?>
                    </select>
                </div>
            </div>

            <div class="control-group">
                <label for="name" class="control-label">路径地址：</label>
                <div class="controls">
                    <input id="url" name="_form[url]" type="text" placeholder="api/test" value="<?php echo $params_data['url'] ?>" type="text">
                </div>
            </div>

            <div class="control-group">
                <label for="params" class="control-label">参数：</label>
                <div class="controls">
            <textarea id="params" name="_form[params]" placeholder="name,测试名,备注;
id,1,备注"><?php echo $params_data['params'] ?></textarea>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"></label>
                <div class="controls">
                    <input type="submit" class="sui-btn btn-success" name="a" value="新增并调用">
                    <input type="submit" class="sui-btn btn-info" name="a" value="调用">
                </div>
            </div>

        </div>

        <div class="span3" id="ret_data_list">

            <?php if($curl_statrs==200){
                $statr_color="color: yellowgreen ";
            }else if($curl_statrs==500){
                $statr_color="color: #f43838 ";
            }else{
                $statr_color="color: #ff7800 ";
            }

            ?>
            <div class="control-group">
                <label  class="control-label">返回状态：</label><div class="controls" style=" <?php echo $statr_color; ?>"><?php echo $curl_statrs; ?></div>
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


<table class="sui-table table-bordered">
    <thead>
    <tr>
        <th>接口名</th>
        <th>域名</th>
        <th>类型</th>
        <th>地址</th>
        <th>参数</th>
        <th>操作</th>
    </tr>

    </thead>
    <tbody>
    <?php if(is_array($api_data) && count($api_data) > 0){
        foreach($api_data as $k => $v){
            ?>
            <tr>
                <td>
                    <a href="<?php echo $this->CreateHref('/api/index' ,array('id' => $v['id'])); ?>"><?php echo $v['name']; ?></a>
                </td>
                <td><?php echo $v['hosts']; ?></td>
                <td><?php echo $this->type_name[$v['type']]; ?></td>
                <td><?php echo $v['url']; ?></td>
                <td><a href="javascript:;"
                       title="<?php echo $v['params']; ?>"><?php echo mb_substr($v['params'] ,0 ,5 ,'utf-8');; ?></a>
                </td>
                <td>
                    <a href="<?php echo $this->CreateHref('/api/index' ,array('id' => $v['id'])); ?>">请求调用</a>
                    <a href="javascript:;" onclick="del('<?php echo $v['id'] ?>')">删除</a>

                </td>
            </tr>
        <?php }
    } ?>
    </tbody>

</table>

<script type="text/javascript">
    var ajax_load_type=1; //防重复请求
    var params_page=1;//默认分页
    var apiId=<?php echo intval($params_data['id']); ?>; //当前调用接口id


    //删除接口
    function del(id) {
        $.post('<?php echo Yii::app()->request->baseUrl; ?>/api/del', {id: id}, function (data) {
            if (data == 't') {
                window.location.href = window.location.href;
            } else {
                alert('失败');
            }
        })
    }
    //删除参数
    function del_params(id) {
        $.post('<?php echo Yii::app()->request->baseUrl; ?>/api/delParams', {apiId:apiId,id: id}, function (data) {
            if (data == 't') {
                window.location.href = window.location.href;
            } else {
                alert('失败');
            }
        })
    }
    //显示接口参数列表
    params_list(apiId);
    //数据
    function params_list(apiId){
        if(!apiId>0) return false;
        if(!ajax_load_type>0) return false;
        ajax_load_type=0;
        $.get('<?php echo Yii::app()->request->baseUrl; ?>/api/AjaxParamsList',{id:apiId,params_page:params_page},function(data){
            if(data){
                $('#params_table_list').html(data);
                ajax_load_type=1;
            }else{
                console.log('apiId='+apiId);
                ajax_load_type=1;
            }
        })
    }

    function JsParamsPage(page){
        params_page=page;
        params_list(apiId);

    }

    function FillParams(id){
        $('#params').val($('#ajax_list_params'+id).data('params'));
    }

</script>



