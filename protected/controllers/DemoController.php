<?php

class DemoController extends Controller
{
      //判断是否可以访问该模块
//    public function beforeAction($action)
//    {
//        if(!Yii::app()->user->checkAccess('public'))
//            $this->redirectStay('/login/index','您无权访问');
//        return parent::beforeAction($action);
//    }

    public function actionIndex(){
        $this->layout='/layouts/utf8';
        echo md5('bbdcs');
        
        echo '<script src="/js/md5.js"></script>';
        echo '<script>alert(hex_md5("bbdcs"));</script>';
    }

    public function actionSql(){
        $date=date('Ymd');
        for($i=0;$i<1000;$i++){
            $sqlval.="('".$date.sprintf("%04d", $i)."'),";
        }

        $sql="INSERT INTO `demo` (`body`) VALUES ".substr($sqlval,0,-1);
        Yii::app()->db->createCommand($sql)->query();
    }
    //分页
    public function actionPage()
	{
        $sql="select count(`id`) as `cnt` FROM `demo` ";
        $model=Yii::app()->db->createCommand($sql)->queryAll();
        $cnt=$model[0]['cnt'];

        $page = new Pagination($cnt);
        $page_list = $page->fpage(array(0,2,3,4,5,6,7,8));


        $sql="select * FROM `demo` $page->limit";
        $model=Yii::app()->db->createCommand($sql)->queryAll();

        $this->render('pagination',array('data'=>$model,'page_list'=>$page_list));
	}
    //ajax分页首页
    public function actionIndexAjax()
    {
        $this->render('index_ajax');
    }
    public function actionAjaxPage1()
    {

        $this->layout=' ';

        $sql="select count(`id`) as `cnt` FROM `demo` ";
        $model=Yii::app()->db->createCommand($sql)->queryAll();
        $cnt=$model[0]['cnt'];
        $per=30;
        $page = new PaginationAjax($cnt,$per,'Page1','','JsName1');
        $page_list = $page->fpage(array(0,2,3,4,5,6,7,8));
        $sql="select * FROM `demo` $page->limit";
        $model=Yii::app()->db->createCommand($sql)->queryAll();

        $this->render('page_ajax_li',array('data'=>$model,'page_list'=>$page_list));
    }
    public function actionAjaxPage2()
    {

        $this->layout=' ';

        $sql="select count(`id`) as `cnt` FROM `demo` ";
        $model=Yii::app()->db->createCommand($sql)->queryAll();
        $cnt=$model[0]['cnt'];
        $per=30;
        $page = new PaginationAjax($cnt,$per,'Page2','','JsName2');
        $page_list = $page->fpage(array(0,2,3,4,5,6,7,8));
        $sql="select * FROM `demo` $page->limit";
        $model=Yii::app()->db->createCommand($sql)->queryAll();

        $this->render('page_ajax_li',array('data'=>$model,'page_list'=>$page_list));
    }
    //在线编辑器
    public function actionEditor()
    {
        echo $_GET['myEditor'];
        $this->render('editor');
    }




}