
<table class="sui-table table-bordered">
    <thead>
        <th>字段</th>
        <th>必选</th>
        <th>类型及范围</th>
        <th>说明</th>
    </thead>
    <tbody>
    <?php
    if(is_array($data)){
        foreach($data as $k=>$v){
    ?>
        <tr><td> <?php echo $v['COLUMN_NAME'] ?></td><td>false</td><td><?php echo $v['COLUMN_TYPE'] ?></td><td><?php echo $v['COLUMN_COMMENT'] ?></td></tr>
    <?
        }
    }
    ?>
    </tbody>
</table>

