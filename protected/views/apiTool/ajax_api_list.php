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
    <?php if(is_array($data) && count($data) > 0){
        foreach($data as $k => $v){
            ?>
            <tr>
                <td>
                    <a href="javascript:;"  onclick="fillData('<?php echo $v['id'] ?>')"><?php echo $v['name']; ?></a>
                </td>
                <td><?php echo $hosts_array[$v['hosts_id']]; ?></td>
                <td><?php echo $this->type_name[$v['type']]; ?></td>
                <td><?php echo $v['url']; ?></td>
                <td>
                    <a href="javascript:;" onclick="fillData('<?php echo $v['id'] ?>')" title="<?php echo $v['params']; ?>"><?php echo mb_substr($v['params'] ,0 ,5 ,'utf-8');; ?></a>
                </td>
                <td>
                    <a href="#hosts_sel" onclick="fillData('<?php echo $v['id'] ?>')" >数据填充</a>
                    <a href="javascript:;" onclick="apiDel('<?php echo $v['id'] ?>')" >删除</a>
                </td>
            </tr>
        <?php }
    }else{
    ?>
        <tr><td colspan="6">暂无数据</td></tr>
    <?php
    }
    ?>
    </tbody>

</table>

<?php echo $page_list ?>


