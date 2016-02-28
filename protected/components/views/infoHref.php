<?php $baseUrl = Yii::app()->request->baseUrl;?><!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>操作提示</title>
<meta http-equiv="refresh" content="<?php echo $this->time;?>; URL=<?php echo $this->link;?>" />
<style>
.tips{ font-size:12px; margin:0 auto;padding:0px 0 0 60px; background:url(/images/tips_bg.jpg) no-repeat left center; color:#333; line-height:21px; height:40px; margin-top:20px;}
.tips b{ color:#000; font-weight:bold; font-size:14px;}
.tips a{ color:#00f; text-decoration:none;}
.tips a:hover{ color:#00b2dc; border-bottom:1px solid #00b2dc;}
</style>
</head>
<body>
<div class="tips">
<b><?php echo $this->info;?></b>
<div>页面将在 <?php echo $this->time;?> 秒后自动跳转.
(<?php echo CHtml::link('如果没有响应您可以点击这里', $this->link, array('name'=>'manualHref'));?>)
<?php echo $this->moreInfo;?></div>
</div>
</body>
</html>