<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
<?php $this->Widget('application.components.DemoWidget'); ?><!--路径：protected\components\views\DomeWidge.php -->
<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>
<a href="<?php echo $this->createUrl('site/index',array('id'=>100,'text'=>'你好啊')) ?>">链接名</a>
<a href="<?php echo Yii::app()->baseUrl; ?>/site/index?id=3&txt=你好啊">链接名</a>
<a href="<?php echo Yii::app()->baseUrl; ?>/site/index?id=3&txt=<?php echo urlencode('你好啊'); ?>">链接名</a>
<p>Congratulations! You have successfully created your Yii application.</p>

<p>You may change the content of this page by modifying the following two files:</p>
<ul>
	<li>View file: <code><?php echo __FILE__; ?></code></li>
	<li>Layout file: <code><?php echo $this->getLayoutFile('main'); ?></code></li>
</ul>

<p>For more details on how to further develop this application, please read
the <a href="http://www.yiiframework.com/doc/">documentation</a>.
Feel free to ask in the <a href="http://www.yiiframework.com/forum/">forum</a>,
should you have any questions.</p>


