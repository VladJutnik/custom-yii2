<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php',
    require __DIR__ . '/main-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'homeUrl' =>'/',
    'language' =>'RU',
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'baseUrl' => '',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            'loginUrl' => ['site/login']
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        //это основная БД
         'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=custom-yii2',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ],
        //это БД где лежат справочная информация что бы не засерать основу и копия бд не были слишком большие!
         'db_constantly' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=constantly',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\EmailTarget',
                    'mailer' => 'mailer',
                    'levels' => ['error'],
                    'message' => [
                        'from' => [''],
                        'to' => [''],
                        'subject' => 'Ошибка в программе',
                    ],
                    'except' => [
                        'yii\web\HttpException:404',
                        'yii\web\HttpException:429',
                        'yii\web\HeadersAlreadySentException'
                    ],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/login' => 'site/login',
                '/profile' => 'user-settings/profile',
                '/settings' => '/user-settings/settings',
            ],
        ],
        'myComponent' => [
            'class' => 'common\components\MyComponent',
        ],
    ],

    'params' => $params,
];
