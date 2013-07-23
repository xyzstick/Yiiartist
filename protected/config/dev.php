<?php
/**
 * Development configuration file
 * This is the main Web application configuration. Any writable
 * CWebApplication properties can be configured here.
 */
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Yii - Development',
	'timezone'=>'UTC',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	/**
	 * Load Modules
	 */
	'modules'=>array(
		//uncomment the following to enable the Gii tool
		'generatorPaths'=>array('bootstrap.gii',),
		// index-dev.php?r=gii
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'password',
			//if removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
			//'newFileMode'=>0666,
			//'newDirMode'=>0777,
		),
	),//*/

	//'defaultController'=>'post',

	// application components
	'components'=>array(
		'user'=>array(
			//enable cookie-based authentication "Remember me next time"
			//'allowAutoLogin'=>true,
			//'class'=>'WebUser',
			'loginUrl'=>array('site/login'), 
		),//*/

		/** 
		 * Cookies Request
		 * CHttpRequest encapsulates the $_SERVER variable and resolves its 
		 * inconsistency among different Web servers.
		 * Enable if enable cookie-based authentication "Remember me next time" is set.
		 */
		/*'request'=>array(
			'enableCookieValidation'=>true,
		),//*/

		/**
		 * URL Mananger
		 */
		/*'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'post/<id:\d+>/<title:.*?>'=>'post/view',
				'posts/<tag:.*?>'=>'post/index',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),//*/

		/**
		 * Databases
		 */
		/*'db'=>array(
			'connectionString'=>'sqlite:protected/data/blog.db',
			'tablePrefix'=>'tbl_',
		),//*/
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString'=>'mysql:host=localhost;dbname=yiiartist',
			'emulatePrepare'=>true,
			'username'=>'root',
			'password'=>'',
			'charset'=>'utf8',
			'tablePrefix'=>'tbl_',
		),//*/
	    
		/**
		 * Authorization Manager
		 * Before we set off to define an authorization hierarchy and perform access 
		 * checking, we need to configure the authManager application component. Yii 
		 * provides two types of authorization managers: CPhpAuthManager and CDbAuthManager. 
		 * The former uses a PHP script file to store authorization data, while the latter 
		 * stores authorization data in database. When we configure the authManager application 
		 * component, we need to specify which component class to use and what are the initial 
		 * property values for the component
		 */
		'authManager'=>array(
			'class'=>'CDbAuthManager',
			'connectionID'=>'db',
			'itemTable'=>'{{AuthItem}}',
			'itemChildTable'=>'{{AuthItemChild}}',
			'assignmentTable'=>'{{AuthAssignment}}',
			'defaultRoles'=>array('costumer','admin'),
		),//*/

		/**
		 * Session
		 * The save path, in case you’re not familiar with it, is where the session data 
		 * is stored on the server. By default, this is a temporary directory, globally 
		 * readable and writable. Every site running on the sever, if there are many (and 
		 * shared hosting plans can have dozens on a single server), share this same directory. 
		 * This means that any site on the server can read any other site’s stored session data. 
		 * For this reason, changing the save path to a directory within your own site can be a 
		 * security improvement. Alternatively, you can store the session data in a database. To  
		 * do that, add this code to the “components” section of protected/config/main.php:
		 */
		'session'=>array(
			'class'=>'system.web.CDbHttpSession',
			'connectionID'=>'db',
			'sessionTableName'=>'{{Session}}',
			'timeout'=>600,//timeout after 10 mins
			/*'cookieParams'=>array(
				// cookie should be sent via secure connection
				//'secure'=>true,
				// cookie should be accessible only through the HTTP protocol. If true Javascript 
				// wont be accessible. which can effectly help to reduce identity theft through XSS attacks.
				//'httpOnly'=>true,
			),//*/
		),//*/

		/**
		 * Error Handler
		 */
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),//*/

		/**
		 * Logging
		 */
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				//array('class'=>'CFileLogRoute','levels'=>'trace,info,error,warning',),
				array('class'=>'CWebLogRoute','levels'=>'trace,info,error,warning',),
			),
		),//*/
	),

	/**
	 * application-level parameters that can be accessed
	 * using Yii::app()->params['paramName']
	 */
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),//*/
	//'params'=>require(dirname(__FILE__).'/params.php'),
);