<?php
/**
 * 微信公众号
 */
class WeiXinGongZhongHaoController extends WeixinClass
{

    public $appId='';
    public $appsecret='';

    /**
     *微信公众号接入
     */
    public function actionIndex(){
        define("TOKEN", "myWink");
        $this->valid();exit;//微信服务器配置时,去掉注释
    }

    public function actionUser(){
        $code=$_GET['code']; //用户确认
        if(empty($code)){ exit; }

        //通过code去
        $url='https://api.weixin.qq.com/sns/oauth2/access_token';
        $jsonData=Yii::app()->curl->get($url,
            array(
                'appid'=>$this->appId,
                'secret'=>$this->appsecret,
                'code'=>$code,
                'grant_type'=>'authorization_code',
            )
        );
        if(empty($jsonData)){exit;}
        $data=CJSON::decode($jsonData);
        

        //通过code去
        $url='https://api.weixin.qq.com/sns/userinfo';
        $jsonData=Yii::app()->curl->get($url,
            array(
                'access_token'=>$data['access_token'],
                'openid'=>$data['openid'],
                'lang'=>'zh_CN',
            )
        );
        if(empty($jsonData)){exit;}
        echo $jsonData;exit;


        //$this->getAccessToken($this->appId,$this->appsecret);
    }

    public function actionT(){
        $url='http://www.mywink.top/weiXinGongZhongHao/User';
       // $scopt='snsapi_base';
        $scopt='snsapi_userinfo';

        echo 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appId.'&redirect_uri='.urlencode($url).'&response_type=code&scope='.$scopt.'&state=STATE#wechat_redirect';

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

    function __construct(){
        $this->appId=Yii::app()->params['wx_appID'];
        $this->appsecret=Yii::app()->params['wx_appsecret'];
    }



}