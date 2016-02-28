<table class="sui-table table-bordered">
    <thead>
        <th>表名</th>
        <th>类型</th>
        <th>自增</th>
        <th>说明</th>
<!--        <th>文档</th>-->
    </thead>
    <tbody>
    <?php

   if(is_array($data)){
        foreach($data as $k=>$v){
    ?>
        <tr>
            <td><a href="<?php echo Yii::app()->baseUrl; ?>/Table/Info?tableName=<?php echo $v['TABLE_NAME'] ?>" target="_blank"> <?php echo $v['TABLE_NAME'] ?></a></td>
            <td><?php echo $v['ENGINE'] ?></td>
            <td><?php echo $v['AUTO_INCREMENT'] ?></td>
            <td><?php echo $v['TABLE_COMMENT'] ?></td>
<!--            <td><a href="--><?php //echo Yii::app()->baseUrl; ?><!--/Table/Doc?tableName=--><?php //echo $v['TABLE_NAME'] ?><!--" target="_blank">文档用说明</a></td>-->
        </tr>
    <?php
        }
    }
    ?>
    </tbody>
</table>

