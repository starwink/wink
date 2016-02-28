<?php $this->pageTitle='Yii demo';?>
<?php $this->Widget('application.components.DemoWidget'); ?><!--路径：protected\components\views\DomeWidge.php -->


<head>
    <title>完整demo</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>


    <style type="text/css">
        div{
            width:100%;
        }
    </style>
</head>
<body>
<div>
    <h1>完整demo</h1>
<form  method="get" action="">
    <script type="text/javascript" charset="utf-8" src="<?php echo Yii::app()->baseUrl; ?>/js/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo Yii::app()->baseUrl; ?>/js/ueditor/ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="<?php echo Yii::app()->baseUrl; ?>/js/ueditor/lang/zh-cn/zh-cn.js"></script>
    <textarea  id="myEditor"  name="myEditor" >初始内容</textarea>
    <script type="text/javascript">
        UE.getEditor('myEditor');
    </script>
<input type="submit" value="223">
</form>
</div>

</div>





</body>