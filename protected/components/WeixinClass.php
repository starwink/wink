<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class WeixinClass extends Controller
{

    /**
     * 微信公众号配置验证
     * @throws Exception
     */
    public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
            ob_clean();
            echo $echoStr;
             //echo mb_convert_encoding($echoStr,"UTF-8", "GBK");
            exit;
        }
    }

    public function getAccessToken($appID,$appSecret){
        $url='https://api.weixin.qq.com/cgi-bin/token';
        $jsonData=Yii::app()->curl->get($url,
            array(
                'appid'=>$appID,
                'secret'=>$this->appsecret,
                'grant_type'=>'client_credential',
            )
        );
        echo $jsonData;
        $data=CJSON::decode($jsonData);
        return $data['access_token'];
    }

    /**
     * 配置公众号方法 用于js api接入
     * @return bool
     * @throws Exception
     */
    private function checkSignature()
    {
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }

        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    /**
     * jssdk config配置签名  (当前微信找房在用)
     *
     * @param string $nonceStr 随机数 如果是config不需要传,其他位置签名需要传config的随机数
     * @param int $timestamp  时间戳 精确到秒 同上
     * @param string $url 调用起支付的页面url 必传
     * @return array 签名数组
     * @author rayzhang
     * @todo  timestamp 不同版本S大小写要求不同  神烦
     *
     */
    protected function getSignPackage($url,$nonceStr='',$timestamp='',$appId='') {
        $jsapiTicket = $this->getTicket();
        //$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $timestamp = $timestamp?$timestamp:time();
        $nonceStr = $nonceStr?$nonceStr:WxPayApi::getNonceStr();
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);
        $signPackage = array(
            "appId"     => WxPayConfig::APPID,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            //             "rawString" => $string
        );
        return $signPackage;
    }
    /**
     * 获取token 如果token过期自动获取并更新数据库
     * @author rayzhang
     */
    protected function getToken(){
        $token_model=new WxToken_ticket();
        $token=$token_model->getAccess_token();
        if (empty($token)){
            $url='https://api.weixin.qq.com/cgi-bin/token';
            $json=Yii::app()->curl->get($url,array('grant_type'=>'client_credential','appid'=>WxPayConfig::APPID,'secret'=>WxPayConfig::APPSECRET));
            $data=json_decode($json,true);
            if($data['access_token']){
                $token_model->upAccess_token($data['access_token'], $data['expires_in']-5);
                return $data['access_token'];
            }else {
                return false;
            }
        }else {
            return $token;
        }
    }
    /**
     * 获取jsapi_ticket 如果过期将会重新请求更新数据库
     * @author rayzhang
     */
    protected function getTicket(){
        $token_model=new WxToken_ticket();
        $jsapi_ticket=$token_model->getTicket();
        if (empty($jsapi_ticket)){
            $url='https://api.weixin.qq.com/cgi-bin/ticket/getticket';
            $token=$this->getToken();
            $json=Yii::app()->curl->get($url,array('access_token'=>$token,'type'=>'jsapi'));
            $data=json_decode($json,true);
            if($data['ticket']){
                $token_model->upTicket($data['ticket'], $data['expires_in']-5);
                return $data['ticket'];
            }else {
                return false;
            }
        }else {
            return $jsapi_ticket;
        }
    }

    /**
     * jssdk config配置签名  (当前微信找房在用)
     * @param string $appId 微信公众号或企业号的appId
     * @param string $nonceStr 随机数 如果是config不需要传,其他位置签名需要传config的随机数
     * @param int $timestamp  时间戳 精确到秒 同上
     * @param string $url 调用起支付的页面url 必传
     * @return array 签名数组
     */
    protected function i_getSignPackage($appId,$url,$nonceStr='',$timestamp='') {
        $jsapiTicket = $this->getTicket();
        //$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $timestamp = $timestamp?$timestamp:time();
        $nonceStr = $nonceStr?$nonceStr:$this->i_createNonceStr();
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);
        $signPackage = array(
            "appId"     => $appId,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            //             "rawString" => $string
        );
        return $signPackage;
    }

    /**
     * 生成随机字符串,用于微信js接入配置
     * @param int $length
     *
     * @return string
     */
    private function i_createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function i_getJsApiTicket() {
        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = json_decode(file_get_contents("jsapi_ticket.json"));
        if ($data->expire_time < time()) {
            $accessToken = $this->i_getAccessToken();
            // 如果是企业号用以下 URL 获取 ticket
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";

            $res = json_decode($this->httpGet($url));
            $ticket = $res->ticket;
            if ($ticket) {
                $data->expire_time = time() + 7000;
                $data->jsapi_ticket = $ticket;
                $fp = fopen("jsapi_ticket.json", "w");
                fwrite($fp, json_encode($data));
                fclose($fp);
            }
        } else {
            $ticket = $data->jsapi_ticket;
        }

        return $ticket;
    }

    private function i_getAccessToken() {
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = json_decode(file_get_contents("access_token.json"));
        if ($data->expire_time < time()) {
            // 如果是企业号用以下URL获取access_token
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
            $res = json_decode($this->httpGet($url));
            $access_token = $res->access_token;
            if ($access_token) {
                $data->expire_time = time() + 7000;
                $data->access_token = $access_token;
                $fp = fopen("access_token.json", "w");
                fwrite($fp, json_encode($data));
                fclose($fp);
            }
        } else {
            $access_token = $data->access_token;
        }
        return $access_token;
    }


    /**
     *公众号回复信息方法
     */
    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){

              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
                $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
				if(!empty( $keyword ))
                {
              		$msgType = "text";
                	$contentStr = "Welcome to wechat world!";
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
                }else{
                	echo "Input something...";
                }

        }else {
        	echo "";
        	exit;
        }
    }
	public function reply($ToUserName,$FromUserName,$CreateTime,$Content,$FuncFlag=0){
		$xml='<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[%s]]></Content><FuncFlag>%u</FuncFlag></xml>';
		return sprintf($xml,$ToUserName,$FromUserName,$CreateTime,$Content,$FuncFlag);
	}
    /**ToUserName FromUserName CreateTime
	接收方帐号	 开发者微信号   消息创建时间 */
	public function replyBase($ToUserName,$FromUserName,$CreateTime){
		$xml='<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime>';
		return sprintf($xml,$ToUserName,$FromUserName,$CreateTime);
	}
	/**Content FuncFlag
	  内容	 星标消息*/	
	public function replyText($Content,$FuncFlag=0){
		$xmlText='<MsgType><![CDATA[text]]></MsgType><Content><![CDATA[%s]]></Content><FuncFlag>%u</FuncFlag></xml>';
		return sprintf($xmlText,$Content,$FuncFlag);
	}
	/**Title   Description	MusicUrl	HQMusicUrl		FuncFlag
	  消息标题  消息描述		音乐链接     (wifi)音乐链接	星标消息*/	
	public function replyMusic($Title,$Description,$MusicUrl,$HQMusicUrl,$FuncFlag=0){
		 $xmlMusic='<MsgType><![CDATA[music]]></MsgType>
		<Music><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description><MusicUrl><![CDATA[%s]]></MusicUrl><HQMusicUrl><![CDATA[%s]]></HQMusicUrl></Music>
		<FuncFlag>%u</FuncFlag></xml>';
		return sprintf($xmlMusic,$Title,$Description,$MusicUrl,$HQMusicUrl,$FuncFlag);
	}
	/**ArticleCount	Title   Description		PicUrl		Url			FuncFlag
	  图文消息个数		消息标题  消息描述			图片链接     点击跳转链接	星标消息*/	
	public function replyNews($ArticleCount,$Title,$Description,$PicUrl,$Url,$FuncFlag=0){
		$xmlOne='<MsgType><![CDATA[news]]></MsgType>';
 		$xmlTwo='<ArticleCount>%u</ArticleCount><Articles>';
		$xmlFor='<item><Title><![CDATA[%s]]></Title> <Description><![CDATA[%s]]></Description><PicUrl><![CDATA[%s]]></PicUrl><Url><![CDATA[%s]]></Url></item>';
		$xmlThree='</Articles><FuncFlag>%u</FuncFlag></xml> ';
	}

}
