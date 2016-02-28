<table class="sui-table table-bordered">
    <thead>
    <tr>
        <th>ID</th>
        <th>参数</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php if(is_array($data) && count($data)>0){
            foreach($data as $k=>$v){
    ?>
        <tr>
            <td><?php echo $v['id'] ?></td>
            <td id="ajax_list_params<?php echo $v['id'] ?>" data-params="<?php echo $v['params'] ?>" title="<?php echo $v['params'] ?>"><?php echo mb_substr($v['params'],0,10,'utf-8'); ?></td>
            <td>
                <a href="javascript:;" onclick="FillParams(<?php echo $v['id'] ?>)">填充</a>
                <a href="javascript:;" class="jq_del_params" onclick="del_params('<?php echo $v['id'] ?>')">删除</a>
            </td>
        </tr>
    <?php }}else{?>
        <tr>
            <td colspan="3">暂无参数</td>
        </tr>
    <?php } ?>

    </tbody>
</table>
<?php echo $page_list; ?>