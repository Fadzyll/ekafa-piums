<?php
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'ekafa-piums',  // ← Better ID
    'name' => 'E-KAFA PIUMS',  // ← Add app name
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'en-MY',  // ← Add language (Malaysian English)
    'timeZone' => 'Asia/Kuala_Lumpur',  // ← Add timezone (matching your schema)
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'pvOvJCYQSJA6fAYvej8zMGFo2wzg82IU',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',  // ← FIXED (singular)
            'enableAutoLogin' => true,
            'loginUrl' => ['site/login'],
            'identityCookie' => [
                'name' => '_identity-ekafa',
                'httpOnly' => true,
            ],
        ],
        
        // ← ADD THIS: RBAC Configuration
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['parent'],  // Default role for new users
        ],
        
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            'useFileTransport' => true,  // ← Change to false in production
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
    
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;