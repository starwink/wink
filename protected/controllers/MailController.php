<?php

/**
 *  发送邮件类
 */
class MailController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actionIndex()
	{
		$message = 'Hello World!';
		$title=Yii::app()->request->getParam('title');
		$body=Yii::app()->request->getParam('body');
		if(empty($title)){exit;}
		//yii::import('application.extensions.phpMailer.phpmailer');
		require_once('protected/extensions/email/class.phpmailer.php');
		$mail = new PHPMailer();   //实例化
		$mail->IsSMTP();                 // 启用SMTP
		$mail->Host="smtp.163.com";      //smtp服务器的名称（这里以126邮箱为例）
		$mail->SMTPAuth = true;         //启用smtp认证
		$mail->Username = "hah626@163.com";   //你的邮箱名
		$mail->Password = "hlm5945";      //邮箱密码

		$mail->From = "hah626@163.com";            //发件人地址（也就是你的邮箱地址）
		$mail->FromName = "wink";              //发件人姓名
		$mail->AddAddress("504870050@qq.com","wink"); //添加收件人
		$mail->AddReplyTo("hah626@163.com", "hlm");    //回复地址(可填可不填)

		$mail->WordWrap = 50;                    //设置每行字符长度
		// $mail->AddAttachment("images/01.jpg", "manu.jpg");   // 添加附件,并指定名称
		$mail->IsHTML(true);                 // 是否HTML格式邮件

		$mail->CharSet="utf-8";    //设置邮件编码
		$mail->Subject = $title;          //邮件主题
		$mail->Body    = $body;        //邮件内容
		//$mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //邮件正文不支持HTML的备用显示

		if(!$mail->Send())
		{
		  echo "Message could not be sent. <p>";
		  echo "Mailer Error: " . $mail->ErrorInfo;
		  exit();
		} else {
		 echo "Message has been sent";
		}
		
	}


}