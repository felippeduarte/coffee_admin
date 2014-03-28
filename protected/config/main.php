<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// Define a path alias for the Bootstrap extension as it's used internally.
// In this example we assume that you unzipped the extension under protected/extensions.
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');
Yii::setPathOfAlias('chartjs', dirname(__FILE__).'/../extensions/yii-chartjs');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    
    'language' => 'pt_br',
	'name'=>'Bulebar - Controle Financeiro',

	// preloading 'log' component
	'preload'=>array('log', 'bootstrap', 'chartjs'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'bulebar',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
                        'generatorPaths'=>array(
                        'bootstrap.gii',
                    ),
		),
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
        'bulebar'=>array(
            'class'=>'application.extensions.bulebar.Funcoes',
        ),
        'chartjs' => array('class' => 'chartjs.components.ChartJs'),
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
                        'showScriptName'=>false,
			'rules'=>array(
                'relatorio/<action:(lancamentoEstabelecimento|'.
                                  'lancamentoFornecedor|'.
                                  'folhaPagamentoMensal|'.
                                  'relatorioGenerico)>'=>'relatorio/<action>',
                'relatorio/<action:\w+>'=>'relatorio/index',
                'cadastro/<action:(getGrupoEstabelecimento|'.
                                  'getEstabelecimento|'.
                                  'getUsuario|'.
                                  'getColaborador|'.
                                  'getCargoColaborador|'.
                                  'getFornecedor|'.
                                  'getCategoriaLancamento|'.
                                  'getFormaPagamento|'.
                                  'getEstabelecimentoFormaPagamento|'.
                                  'delEstabelecimentoFormaPagamento|'.
                                  'delFormaPagamento|'.
                                  'delGrupoEstabelecimento|'.
                                  'delEstabelecimento|'.
                                  'delPessoa|'.
                                  'delCargoColaborador|'.
                                  'delUsuario|'.
                                  'delCategoriaLancamento)>'=>'cadastro/<action>',
                'cadastro/<action:\w+>'=>'cadastro/index',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                
			),
		),
		
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=bulebar',
			'emulatePrepare' => true,
			'username' => 'bulebar',
			'password' => 'bulebar',
			'charset' => 'utf8',
            'enableProfiling'=>true,
            'enableParamLogging'=>true,
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
				),
				// uncomment the following to show log messages on web pages
				
				/*array(
					'class'=>'CWebLogRoute',
                    'categories'=>'system.db.*',
				),*/
				
			),
		),
                'bootstrap' => array(
                    'class' => 'ext.bootstrap.components.Bootstrap',
                    'responsiveCss' => true,
                ),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'felippeduarte@gmail.com',
	),
        'theme'=>'bootstrap', // requires you to copy the theme under your themes directory
    
        //forçar login
        'behaviors' => array(
          'onBeginRequest' => array(
                'class' => 'application.components.RequireLogin'
            )
        ),
);