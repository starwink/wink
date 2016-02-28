<?php
/*
 * 配置文件
 */
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'wink',
	'defaultController'=>'site', //设置默认控制器
	'language'=>'zh_cn',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool

//		'gii'=>array(
//			'class'=>'system.gii.GiiModule',
//			'password'=>'Enter Your Password Here',
//			// If removed, Gii defaults to localhost only. Edit carefully to taste.
//			//'ipFilters'=>array('127.0.0.1','::1'),
//		),

	),

	// application components
	'components'=>array(

		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),

		// uncomment the following to enable URLs in path-format

		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName' => false, //隐藏index.php
			'urlSuffix' => '.html',
			'caseSensitive' => true, //设置对大小写不敏感
			//'showScriptName'=>FALSE,
			'rules'=>array(

				'<controller:\w+>/<action:\w+>/<id:\d+.html>'=>'<controller>/<action>',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>/<condition:\w+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		//加入curl类
		'curl' => array('class'=>'application.extensions.Curl'),

        'db'=>array(
            'connectionString' => 'mysql:host=stable IP;dbname=stable database name',
            'emulatePrepare' => true,
            'username' => 'stable username',
            'password' => 'stable password',
            'charset' => 'utf8mb4',
           // 'tablePrefix' => 'tbl_',
        ),
		

		'db2'=>array(
			'class' => 'CDbConnection',
			'connectionString' => 'mysql:host=stable IP;dbname=information_schema',
			'emulatePrepare' => true,
			'username' => 'stable username',
			'password' => 'stable password',
			'charset' => 'utf8mb4',
		),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),

	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']  //调用方式
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
		'wx_appID'=>'stable appID',
		'wx_appsecret'=>'stable appsecret',
		'wx_EncodingAESKey'=>'stable EncodingAESKey'
	),
);
