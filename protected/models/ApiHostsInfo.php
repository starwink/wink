<?php

/**
 * 查看字段表
 */
class ApiHostsInfo extends ModelCActiveRecord
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
        return 't_api_hosts_info';
    }

    /**
     * 过滤字段
     */
    public function rules()
    {
        return array
        (
            array('hosts_name,hosts', 'safe'),
        );
    }


    /**
     * 返回域名ID 确保域名唯一性
     *
     * @param string $hosts     域名地址
     * @param string $hostsName 域名地址名称
     *
     * @return int
     */
    public function retHostsIdForHostsName($hosts,$hostsName=''){
        if(empty($hosts)){ return 0; }
        //查询是否已存在
        $sql="SELECT `id`,`hosts` FROM ".$this->tableName()." WHERE `hosts`='$hosts' ";
        $model=Yii::app()->db->createCommand($sql)->queryAll();
        if(count($model)>0){
            return $model[0]['id'];
        }else{
            $table_data = new ApiHostsInfo();
            $table_data->hosts = $hosts;
            if($hostsName==''){$hostsName=$hosts;}
            $table_data->hosts_name = $hostsName;
            $table_data->save();
            return Yii::app()->db->getLastInsertID();
        }
    }

    /**
     * 查询所有hosts信息
     *
     * @return array
     */
    public function IFindAll(){
        $sql="select * from  ".$this->tableName()."  ";
        $model=Yii::app()->db->createCommand($sql)->queryAll();
        return $model;
    }

    /**
     * 返回域名id对应域名名称数组
     *
     * @return array
     */
    public function HostsNameForHostsIdArray(){
        $retArray=array();
        $sql="SELECT `id`,`hosts` FROM ".$this->tableName();
        $model=Yii::app()->db->createCommand($sql)->queryAll();
        foreach($model as $k=>$v){
            if(!$v['id']>0){ continue; }
            $retArray[$v['id']]=$v['hosts'];
        }
        return $retArray;
    }


}

?>