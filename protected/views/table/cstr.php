<div class="grid-demo">
    <div class="sui-row">
        <div class="span4">
            <form method="get" class="sui-form form-horizontal">

                <div class="control-group">
                    <label for="url" class="control-label">表名：</label>
                    <div class="controls">
                        <input type="text" placeholder="表名" name="tableName" value="<?php echo $_GET['tableName'] ?>">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label"></label>
                    <div class="controls">
                        <input type="submit" class="sui-btn btn-success" value="提交">
                    </div>
                </div>

            </form>
        </div>
        <div class="span6">
            <div class="control-group">
                <label class="control-label"></label>
                <div class="controls">
                    <textarea style="height: 200px; width:100%;"><?php echo $data ?></textarea>
                </div>
            </div>
        </div>
    </div>

</div>
