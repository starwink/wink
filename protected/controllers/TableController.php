<?php

class TableController extends Controller
{

    private $dataBase_Name = 'wink';

    public function actionIndex()
    {
        $this->pageTitle = '整库表';
        $criteria = new CDbCriteria;

        $criteria->select = 'TABLE_NAME,ENGINE,AUTO_INCREMENT,TABLE_COMMENT';
        $criteria->condition = 'TABLE_SCHEMA=:a ';
        $criteria->params = array(':a' => $this->dataBase_Name ,);
        $table_data = DevTables::model()->findAll($criteria);
        $this->render('/table/index' ,array('data' => $table_data));

    }

    public function actionInfo()
    {
        $tableName = $_GET['tableName'];
        $criteria = new CDbCriteria;
        $criteria->select = 'TABLE_NAME,ENGINE,AUTO_INCREMENT,TABLE_COMMENT';
        $criteria->condition = 'TABLE_SCHEMA=:a ';
        $criteria->params = array(':a' => $this->dataBase_Name ,);
        $table_data = DevTables::model()->findAll($criteria);

        $criteria = new CDbCriteria;
        //$criteria->select='COLUMN_NAME,COLUMN_TYPE,COLUMN_KEY,COLUMN_COMMENT';
        $criteria->condition = 'TABLE_SCHEMA=:a and `TABLE_NAME`=:b ';
        $criteria->params = array(':a' => $this->dataBase_Name ,':b' => $tableName);
        $columns_data = DevColumns::model()->findAll($criteria);

        $this->pageTitle=$tableName.' - '.$table_data[0]->TABLE_COMMENT;
        $this->render('/table/info' ,array('data' => $columns_data,'table_data'=>$table_data[0],));

    }

    public function actionAll()
    {
        $criteria = new CDbCriteria;

        $criteria->select = 'TABLE_NAME,ENGINE,AUTO_INCREMENT,TABLE_COMMENT';
        $criteria->condition = 'TABLE_SCHEMA=:a ';
        $criteria->params = array(':a' => $this->dataBase_Name ,);
        $table_data = DevTables::model()->findAll($criteria);
        $info = array();
        foreach($table_data as $k => $v){
            $criteria = new CDbCriteria;
            $criteria->condition = 'TABLE_SCHEMA=:a and `TABLE_NAME`=:b ';
            $criteria->params = array(':a' => $this->dataBase_Name ,':b' => $v['TABLE_NAME']);
            $info[$v['TABLE_NAME']] = DevColumns::model()->findAll($criteria);
        }

        $this->pageTitle='整库表字段';
        $this->render('all' ,array('table_data' => $table_data ,'info' => $info));

    }

    public function actionDoc()
    {
        $tableName = $_GET['tableName'];
        $criteria = new CDbCriteria;
//$criteria->select='COLUMN_NAME,COLUMN_TYPE,COLUMN_KEY,COLUMN_COMMENT';
        $criteria->condition = 'TABLE_SCHEMA=:a and `TABLE_NAME`=:b ';
        $criteria->params = array(':a' => $this->dataBase_Name ,':b' => $tableName);
        $table_data = DevColumns::model()->findAll($criteria);
        $this->render('doc' ,array('data' => $table_data));
    }

    public function actionCstr(){
        if($_GET['tableName']){
            $tableName=$_GET['tableName'];
            $sql='SELECT * FROM `COLUMNS` WHERE `TABLE_SCHEMA`=\''.$this->dataBase_Name.'\' and `TABLE_NAME`= \''.$tableName.'\'';
            $columns_data  = Yii::app()->db2->createCommand($sql)->queryAll();
            $str='';
            foreach($columns_data as $k =>$v){
                $str=='' ? $str=$v['COLUMN_NAME'] : $str.=','.$v['COLUMN_NAME'];
            }
        }

        $this->render('/table/cstr' ,array('data' => $str));
    }


}