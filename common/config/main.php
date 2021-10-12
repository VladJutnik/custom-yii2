<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'message' => [
            'class' => 'common\components\MessageComponent',
        ],
        'myComponent' => [
            'class' => 'common\components\MyComponent',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
];
