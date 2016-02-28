<?php

if(is_array($table_data)){
foreach($table_data as $k=>$v){
?>
<table  class="sui-table table-bordered">
    <thead>
        <tr>
            <th>表名</th>
            <th>类型</th>
            <th>自增</th>
            <th>说明</th>
        </tr>
        <tr>
            <th><a href="<?php echo Yii::app()->baseUrl; ?>/Table/Info?tableName=<?php echo $v['TABLE_NAME'] ?>" target="_blank"> <?php echo $v['TABLE_NAME'] ?></a></th>
            <th><?php echo $v['ENGINE'] ?></th>
            <th><?php echo $v['AUTO_INCREMENT'] ?></th>
            <th><?php echo $v['TABLE_COMMENT'] ?></th>
        </tr>
    </thead>
    <tbody>
    <tr>
        <td>字段</td>
        <td>类型</td>
        <td>主键</td>
        <td>说明</td>
    </tr>

        <?php if(is_array($info[$v['TABLE_NAME']])){
                foreach($info[$v['TABLE_NAME']] as $a =>$b){
        ?>

        <tr>
            <td><?php echo $b['COLUMN_NAME'] ?></td>
            <td><?php echo $b['COLUMN_TYPE'] ?></td>
            <td><?php echo $b['COLUMN_KEY'] ?></td>
            <td><?php echo $b['COLUMN_COMMENT'] ?></td></tr>
        <?php }
        }
        ?>

        <tr><td colspan="4"></td></tr>

    </tbody>
</table>
<?php
}
}
?>

