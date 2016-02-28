<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
   public $layout='//layouts/main';
    public $navbarName='';
    // public $layout='//layouts/main';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

    /**
     * 跳转提示窗口
     *
     * @param string $link 地址
     * @param string $info 说明文字
     * @param int    $time 跳转等待秒数
     * @param string $moreInfo
     */
    public function redirectStay($link ,$info ,$time = 3 ,$moreInfo = '')
    {
        $this->widget('application.components.InfoHref' ,array('info' => $info ,'link' => $link ,'time' => $time ,'moreInfo' => $moreInfo));
        exit;
    }

    /**
     * 创建url链接
     *
     * @param string $url  路径
     * @param array  $params 参数
     */
    public function CreateHref($url,$params){
        $hosts= 'http://'.$_SERVER['HTTP_HOST']; //https nginx当前没有方式来判断是否是https协议
        $params_url='';
        if(is_array($params)){
            foreach($params as $k=>$v){
                if(empty($k)){ continue;}
                $params_url.='/'.$k.'/'.$v;
            }
        }
        //通过url来判断中的.html来判断是否链接后加.html
        if(strstr($url,'.html')){
            return $hosts.str_replace('.html','',$url).$params_url.'.html';
        }else{
            return $hosts.$url.$params_url;
        }
    }

}