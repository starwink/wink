<?php

/**
 * 接口返回值 流水表
 */
class ApiReturnDataLog extends ModelCActiveRecord
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
        return 't_api_return_data_log';
    }

    /**
     * 过滤字段
     */
    public function rules()
    {
        return array
        (
            array('stats,data,url,api_id,time,date,dynamic_params,params', 'safe'),
        );
    }


}

?>