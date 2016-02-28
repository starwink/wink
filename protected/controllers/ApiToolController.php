<?php
/**
 * api接口调试工具控制器
 */
class ApiToolController extends Controller
{
    public $navbarName='ApiTool';
    private $dataBase_name = 'wink';
    public $type_data=array(array('id'=>1,'name'=>'GET'),array('id'=>2,'name'=>'POST'),array('id'=>3,'name'=>'JSON'));
    public $type_name=array('1'=>'GET','2'=>'POST','3'=>'JSON');


    /**
     *构造函数 用来初始化当前控制器的公共变量
     */
    public function __construct()
    {
      //$type_data=db&file
      //$type_name=db&file
    }

    /**
     *主页
     */
    public function actionIndex()
    {
        $this->pageTitle = 'API接口调试';

        //查询所有域名信息
        $hostsData=ApiHostsInfo::model()->IFindAll();
        //请求方式
        $type_data=$this->type_data;

        $this->render('/apiTool/index' ,array('hostsData'=>$hostsData,'type_data'=>$type_data));

    }

    /**
     *AJAX 接口列表
     */
    public function actionAjaxApiList()
    {
        $this->layout='/layouts/utf8';

        $page=intval($_POST['page']);
        $size=intval($_POST['size']);

        $cnt=ApiUrlInfo::model()->retDataCount();
        $per=5;
        $page = new PaginationAjax($cnt,$per,'api_page','','showApiList','POST');

        $DataList=ApiUrlInfo::model()->retDataList(" ORDER BY `id` DESC $page->limit");

        $hostsArray=ApiHostsInfo::model()->HostsNameForHostsIdArray();
        $this->render('/apiTool/ajaxApiList',array('data'=>$DataList,'page_list'=>$page->fpage(),'hosts_array'=>$hostsArray));
    }

    /**
     *ajax方式返回api调用所需信息
     */
    public function actionAjaxFillApiData()
    {
        $this->layout='layouts/utf8';
        $params_data=ApiUrlInfo::model()->IFindId(intval($_GET['id']));

        echo_json_exitc(array('status'=>200,'msg'=>'','obj'=>$params_data));
    }

    /**
     *ajax方式删除api信息
     * 缺少 关联删除
     */
    public function actionAjaxApiDel()
    {
        $this->layout='/layouts/utf8';

        $id=intval($_POST['id']);
        if(ApiUrlInfo::model()->IDeleteForId($id)){
            echo 't';
        }else{
            echo 'f';
        }
    }

    /**
     *ajax方式调用接口
     */
    public function actionAjaxRequestApi()
    {
        $this->layout='/layouts/utf8';

        $jsonData=($_POST['json']);
        $save_type=intval($_POST['type']); //0调用 1新增
        $hosts_sel=$_POST['hosts_sel']; //下拉框域名
        $hosts_cleck=intval($_POST['hosts_cleck']); //是否锁定下拉框域名

        $area=Iclev($_REQUEST['area']);//标识,用来自动化测试接口来区分批次

        if(empty($jsonData)){ echo_json_exitc(array('status'=>200,'msg'=>'json为空','obj'=>array()));}
        $params_data=CJSON::decode($jsonData);


        if($save_type>0 && $params_data['hosts_cleck_type']!=1){
            $hostsId=ApiHostsInfo::model()->retHostsIdForHostsName($params_data['hosts']);
            $table_data = new ApiUrlInfo();
            $table_data->hosts_id=$hostsId;
            $table_data->attributes = $params_data;
            $table_data->save();
            $params_data['id']=Yii::app()->db->getLastInsertID();
        }
        //存储接口参数
        ApiParamsInfo::model()->SaveParams($params_data['id'],$params_data['params']);

        //***调用接口开始计时***
        $pagestartime=microtime();

        //接口调用
        if($hosts_cleck>0){
            $url=$hosts_sel.$params_data['url'];
        }else{
            $url=$params_data['hosts'].$params_data['url'];
        }
        if(!empty($url)){
            //接口参数整理

            $curl_params=array();
            if( !empty($params_data['dynamic_params'])){
                $params=explode(';',ClearFormat($params_data['dynamic_params']));
                foreach($params as $k =>$v){
                    if(empty($v)){ continue;}
                    $a=explode(',',$v);
                    if(empty($a[0])){ continue;}
                    $curl_params[$a[0]]=$a[1];
                }
            }

            if( !empty($params_data['params']) && $params_data['type']!=3 ){
                $params=explode(';',ClearFormat($params_data['params']));
                foreach($params as $k =>$v){
                    if(empty($v)){ continue;}
                    $a=explode(',',$v);
                    if(empty($a[0])){ continue;}
                    $curl_params[$a[0]]=$a[1];
                }
            }




            if($params_data['type']==1){
                $params_data['typeName']='GET';
                $params_data['retData']= Yii::app()->curl->get($params_data['hosts'].$params_data['url'], $curl_params);
            }elseif($params_data['type']==2){
                $params_data['typeName']='POST';
                $params_data['retData']= Yii::app()->curl->post($params_data['hosts'].$params_data['url'], $curl_params);
            }elseif($params_data['type']==3){
                $params_data['typeName']='JSON';
                $params_data['retData']= Yii::app()->curl->json($params_data['hosts'].$params_data['url'], $curl_params);
            }
            $params_data['runUrl']=$url;
            $params_data['runStatus']=Yii::app()->curl->_status;
        }
        //***调用接口结束***
        $pageendtime = microtime();
        $starttime = explode(" ",$pagestartime);
        $endtime = explode(" ",$pageendtime);
        $totaltime = $endtime[0]-$starttime[0]+$endtime[1]-$starttime[1];
        $timecost = sprintf("%s",$totaltime);
        $params_data['runTime']=$timecost;

        //记住接口返回参数
        try{
            $time=time();
            $api_log=new ApiReturnDataLog();
            $api_log->dynamic_params=$params_data['dynamic_params'];
            $api_log->params=$params_data['params'];
            $api_log->stats=$params_data['runStatus'];
            $api_log->url=$params_data['runUrl'];
            $api_log->data=$params_data['retData'];
            $api_log->time=$time;
            $api_log->date=date('Y-m-d',$time);

            if(!empty($area)){
                $api_log->area=$area;
            }
            $api_log->run_time=$params_data['runTime'];

            if($params_data['id']>0){
                $api_log->api_id=$params_data['id'];
            }
            $api_log->save();
        } catch (Exception $e) {
            //出错就不要了,之后考虑做个日志存储方法
        }

        echo_json_exitc($params_data);
    }

    //删除接口申请
    public function actionDelParams()
    {
        $this->layout=false;
        $id=intval($_POST['id']);
        $apiId=intval($_POST['apiId']);
        if(ApiParamsInfo::model()->IDeleteForId($id,$apiId)){
            echo 't';
        }else{
            echo 'f';
        }
    }


    /**
     * 接口调试demo 输出接收参数JSON串
     */
    public function actionTest()
    {
        echo_json_exitc($_REQUEST);
    }

    //接口请求历史参数历史列表
    public function actionAjaxParamsList()
    {
        $this->layout=false;
        $api_id=$_POST['id'];
        $per=5;//分页数
        if($api_id>0){
            $cnt=ApiParamsInfo::model()->CountForApiId($api_id);
            if($cnt>$per){
                $page = new PaginationAjax($cnt,$per,'params_list_page','','JsParamsPage','POST');
                $page_list = $page->fpage(array(4,5,6));
            }
            $model=ApiParamsInfo::model()->ListForApiId($api_id,$page->limit);
        }
        $this->render('/apiTool/ajax_params_list',array('data'=>$model,'page_list'=>$page_list));

    }






}