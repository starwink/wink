<?php

/**
 * 查看字段表
 */
class DevColumns extends DevCActiveRecord
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
        return 'COLUMNS';
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