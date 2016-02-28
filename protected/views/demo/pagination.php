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
    <ul>
        <?php foreach($data as $k=>$v){ ?>
            <li><?php echo $v['body'] ?></li>
        <?php } ?>
    </ul>
    <?=$page_list; ?>
</div>

</div>





</body>