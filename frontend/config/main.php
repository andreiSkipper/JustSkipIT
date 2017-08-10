<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => true,
            'enableStrictParsing' => true,
            //'suffix' => '.html',
            'rules' => [
                // site routes
                '' => 'site/index',
                'about' => 'site/about',
                'contact' => 'site/contact',
                'logout' => 'site/logout',
                'login' => 'site/login',
                'signup' => 'site/signup',
                'request-password-reset' => 'site/request-password-reset',
                'reset-password' => 'site/reset-password',
                'site/language' => 'site/language',
                // profiles routes
                'profile' => 'profiles/my-profile',
                'user/edit-profile' => 'profiles/update',
                'edit-profile' => 'profiles/update',
                'user/<any>' => 'profiles/index',
                // action routest
                'add-post' => 'actions/create',
                'delete-post' => 'actions/delete',
                'edit-post' => 'actions/edit',
                // movies routes
                'movies' => 'movies/index',
                'movies/index' => 'movies/index',
                'movies/view' => 'movies/view',
                // chat routes
                'chat' => 'chat/index',
                'chat/index' => 'chat/index',
                // profile shortURL
                '<any>' => 'profiles/find-by-username'
            ],
        ],
    ],
    'params' => $params,
];
