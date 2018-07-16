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
            'showScriptName' => false,
            'enableStrictParsing' => true,
            //'suffix' => '.html',
            'rules' => [
                // site routes
                '' => 'site/index',
                'navbar' => 'site/navbar',
                'about' => 'site/about',
                'contact' => 'site/contact',
                'logout' => 'site/logout',
                'login' => 'site/login',
                'signup' => 'site/signup',
                'request-password-reset' => 'site/request-password-reset',
                'reset-password' => 'site/reset-password',
                'site/language' => 'site/language',
                'site/auth' => 'site/auth',
                'privacy-policy' => 'site/privacy-policy',
                // profiles routes
                'profile' => 'profiles/my-profile',
                'user/edit-profile' => 'profiles/update',
                'edit-profile' => 'profiles/update',
                'user/<any>' => 'profiles/index',
                // action routes
                'add-post' => 'actions/create',
                'delete-post' => 'actions/delete',
                'edit-post' => 'actions/edit',
                'post/<id>' => 'actions/view',
                // movies routes
                'movies' => 'movies/index',
                'movies/index' => 'movies/index',
                'movies/view' => 'movies/view',
                // chat routes
                'chat' => 'chat/index',
                'chat/index' => 'chat/index',
                // comments routes
                'add-comment' => 'comments/create',
                'delete-comment' => 'comments/delete',
                'edit-comment' => 'comments/edit',
                // comments routes
                'add-friend' => 'friendships/create',
                'delete-friend' => 'friendships/delete',
                'accept-friend' => 'friendships/accept',
                'refuse-friend' => 'friendships/refuse',
                // notifications
                'notifications' => 'site/notifications',
                'notifications/read' => 'site/read-notifications',
                // profile shortURL
                '<any>' => 'profiles/find-by-username',
            ],
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\Google',
                    'clientId' => '295995674087-2dkh1u5obokosascnj74231hoof3m6cb.apps.googleusercontent.com',
                    'clientSecret' => 'daMYSddwgejDkhzsVN4hJEtI',
                ],
                'twitter' => [
                    'class' => 'yii\authclient\clients\Twitter',
                    'consumerKey' => 'UAatvuaGwvrYuUYPcCk83D1KA',
                    'consumerSecret' => 'gOWHoahMfUrPNy0qg8Of9BUa3Mw9EhyzXSOVtDnzRuLeEvKOVf',
                ],
                'linkedin' => [
                    'class' => 'yii\authclient\clients\LinkedIn',
                    'clientId' => '78llh9jfc2pfps',
                    'clientSecret' => '6e2jldcv2fi9Hsyp',
                ],
//                'facebook' => [
//                    'class' => 'yii\authclient\clients\Facebook',
//                    'authUrl' => 'https://www.facebook.com/dialog/oauth?display=popup',
//                    'clientId' => '213333686082552',
//                    'clientSecret' => '6dd01f082481880b84508fbd95c5afe5',
//                ],
            ],
        ],
        'socialShare' => [
            'class' => \ymaker\social\share\configurators\Configurator::class,
            'socialNetworks' => [
                'facebook' => [
                    'class' => \ymaker\social\share\drivers\Facebook::class,
                    'label' => '',
                    'options' => ['class' => 'fb fa fa-facebook-square'],
                ],
                'twitter' => [
                    'class' => \ymaker\social\share\drivers\Twitter::class,
                    'label' => '',
                    'options' => ['class' => 'tw fa fa-twitter-square'],
                    'config' => [
                        'account' => $params['twitterAccount']
                    ],
                ],
//                'whatsapp' => [
//                    'class' => \ymaker\social\share\drivers\WhatsApp::class,
//                    'label' => '',
//                    'options' => ['class' => 'wp fa fa-whatsapp'],
//                ],
//                'pinterest' => [
//                    'class' => \ymaker\social\share\drivers\Pinterest::class,
//                    'label' => '',
//                    'options' => ['class' => 'pn fa fa-pinterest'],
//                ],
                'linkedin' => [
                    'class' => \ymaker\social\share\drivers\LinkedIn::class,
                    'label' => '',
                    'options' => ['class' => 'ln fa fa-linkedin-square'],
                ],
            ],
            'options' => [
                'class' => 'social-network',
            ],
        ],
    ],
    'params' => $params,
    'modules' => [
        'social' => [
            // the module class
            'class' => 'kartik\social\Module',

            // the global settings for the Disqus widget
            'disqus' => [
                'settings' => ['shortname' => 'DISQUS_SHORTNAME'] // default settings
            ],

            // the global settings for the Facebook plugins widget
            'facebook' => [
                'appId' => '167738380465356',
                'secret' => '3a78e2e24a99339e837cb3c6ffed5547',
            ],

            // the global settings for the Google+ Plugins widget
            'google' => [
                'clientId' => 'GOOGLE_API_CLIENT_ID',
                'pageId' => 'GOOGLE_PLUS_PAGE_ID',
                'profileId' => 'GOOGLE_PLUS_PROFILE_ID',
            ],

            // the global settings for the Google Analytics plugin widget
            'googleAnalytics' => [
                'id' => 'TRACKING_ID',
                'domain' => 'TRACKING_DOMAIN',
            ],

            // the global settings for the Twitter plugin widget
            'twitter' => [
                'screenName' => 'darkbanel'
            ],

            // the global settings for the GitHub plugin widget
            'github' => [
                'settings' => ['user' => 'GITHUB_USER', 'repo' => 'GITHUB_REPO']
            ],
        ],
        // your other modules
    ]
];
