<?php

/**
 * 表名表
 */
class DevTables extends DevCActiveRecord
{
    /**
     * model 方法类
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * 设置表名
     */
    public function tableName()
    {
        return 'TABLES';
    }

    /**
     * 过渡字段
     */
    public function rules()
    {
        return array
        (
            //array('id,agent_uid,owner_uid', 'safe'),
        );
    }
}

?>