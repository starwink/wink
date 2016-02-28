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
    <script type="text/javascript" charset="utf-8" src="<?php echo Yii::app()->baseUrl; ?>/js/jquery.min.js"></script>
    <script language="javascript">
        function JsName1(page){
            $.get("AjaxPage1", {Page1:page}, function(data){
                if(data.length >0) {
                    $("#div1").html(data);
                }
            });
        }
        function JsName2(page){
            $.get("AjaxPage2", {Page2:page}, function(data){
                if(data.length >0) {
                    $("#div2").html(data);
                }
            });
        }
        JsName1(1);JsName2(1);
    </script>
</head>
<body>
<div id="div1">


</div>
<div id="div2">


</div>

</div>





</body>