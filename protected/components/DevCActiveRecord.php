<?php
/**
 * 查询开发数据库表结构
 */
class DevCActiveRecord extends BaseCActiveRecord {
    /**
     * 设置链接数据库
     */
    public function getDbConnection() {
        return Yii::app()->db2;
    }

    
}
