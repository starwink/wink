<?php

/**
 * 接口返回值 流水表
 */
class ApiParamsInfo extends ModelCActiveRecord
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
        return 't_api_params_info';
    }

    /**
     * 过滤字段
     */
    public function rules()
    {
        return array
        (
            array('api_id,params,remarks', 'safe'),
        );
    }


    /**
     * 存储参数
     * @param $appId  接口id
     * @param $paramsStr  接口参数集字符串
     */
    public function SaveParams($appId,$paramsStr){
        if(empty($appId) || empty($paramsStr) ){ return false; }
        $sql="SELECT `id` FROM `".$this->tableName()."` WHERE `api_id`='".$appId."' and `params`='".$paramsStr."'";
        $model=Yii::app()->db->createCommand($sql)->queryAll();
        if(intval($model[0]['id']==0)){
            $table=new ApiParamsInfo();
            $table->api_id=$appId;
            $table->params=$paramsStr;
            $table->save();
            return Yii::app()->db->getLastInsertID();
        }else{
           return $model[0]['id'];
        }
    }


    /**
     * 删除接口通过id
     * @param $id
     * @param $apiId 接口id
     *
     * @return bool
     */
    public function IDeleteForId($id,$apiId){
        if(!$id>0 || !$apiId>0){ return false;}
        $sql="DELETE FROM `".$this->tableName()."` WHERE `id`='$id' and `api_id`='$apiId'";
        if(Yii::app()->db->createCommand($sql)->query()){
            return true;
        }else{
            return false;
        }

    }

    /**
     * 通过api id 统计接口下的参数总数
     * @param $apiId
     * @return int
     */
    public function CountForApiId($apiId){
        if(!$apiId>0){return 0;}
        $sql="SELECT `id` FROM `".$this->tableName()."` Where `api_id`=:api_id ";
        $posts=ApiParamsInfo::model()->findAllBySql($sql,array(':api_id'=>$apiId));
        return count($posts);
    }

    /**
     * 通过接口id返回参数列表
     * @param $apiId
     * @param $limit
     *
     * @return array|bool
     */
    public function ListForApiId($apiId,$limit){
        if(!$apiId>0){return false;}
        $sql="SELECT `id`,`api_id`,`params`,`remarks` FROM `".$this->tableName()."` Where `api_id`=:api_id  $limit";
        $model=ApiParamsInfo::model()->findAllBySql($sql,array(':api_id'=>$apiId));
        return yiiObjectToArray($model);
    }
}

?>