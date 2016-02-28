<?php

/**
 * 查看字段表
 */
class ApiUrlInfo extends ModelCActiveRecord {
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
    public function tableName(){
        return 't_api_url_info';
    }

    /**
     * 过滤字段
     */
    public function rules(){
        return array(array('url,type,params,name' ,'safe') ,);
    }

    /**
     * 查询所有接口
     */
    public function IFindAll(){
        $sql = "select *,`t_api_url_info`.`id` as `id` from  " . $this->tableName() . "  LEFT JOIN `t_api_hosts_info`ON `" . $this->tableName() . "`.`hosts_id`=`t_api_hosts_info`.`id`";
        $model = Yii::app()->db->createCommand($sql)->queryAll();
        return $model;
    }

    /**
     * 查询接口通过id
     */
    public function IFindId($id){
        if( !$id > 0){
            return false;
        }
        $sql = "select *,`" . $this->tableName() . "`.`id` as `id` FROM  " . $this->tableName() . "  LEFT JOIN `t_api_hosts_info` ON `" . $this->tableName() . "`.`hosts_id`=`t_api_hosts_info`.`id` WHERE `" . $this->tableName() . "`.`id`=$id";
        $model = Yii::app()->db->createCommand($sql)->queryAll();
        return $model[0];
    }

    /**
     * 删除接口通过id
     */
    public function IDeleteForId($id){
        $sql = "DELETE FROM `" . $this->tableName() . "` WHERE `id`='$id'";
        if(Yii::app()->db->createCommand($sql)->query()){
            return true;
        }else{
            return false;
        }

    }

    /**
     * 返回数据总条数
     *
     * @param string $where SQL条件
     * @return array
     */
    public function retDataCount($where=''){
        $sql = "SELECT count(`id`) as `cnt` FROM `" . $this->tableName() . "` WHERE 1=1 " . $where;
        $model = Yii::app()->db->createCommand($sql)->queryAll();
        return $model[0]['cnt'];
    }

    /**
     * 返回列表数据
     *
     * @param string $where SQL条件
     * @return array
     */
    public function retDataList($where=''){
        $sql = "SELECT * FROM `" . $this->tableName() . "` WHERE 1=1 " . $where;
        $model = Yii::app()->db->createCommand($sql)->queryAll();
        return $model;
    }

}

?>