<?php
\yii\bootstrap\NavBar::begin([
//        'brandLabel' => 'Skipping IT',
    'brandLabel' => \yii\helpers\Html::img('@web/uploads/logos/JustSkipIT_logo1.png', ['alt' => Yii::$app->name, 'class' => 'navbar-logo']),
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);
$menuItems = [
//        ['label' => 'Home', 'url' => ['/site/index']],
//        ['label' => 'About', 'url' => ['/site/about']],
//        ['label' => 'Contact', 'url' => ['/site/contact']],
];
if (Yii::$app->user->isGuest) {
    $menuItems[] = [
        'label' => '<i class="fa fa-file-text-o faa-wrench"></i>',
//            'url' => ['/site/signup'],
        'options' => [
            'id' => 'nav-signup'
        ],
        'linkOptions' => [
            'class' => 'faa-parent animated-hover',
        ],
    ];
    $menuItems[] = [
        'label' => '<i class="fa fa-sign-in faa-horizontal"></i>',
//            'url' => ['/site/login'],
        'options' => [
            'id' => 'nav-login'
        ],
        'linkOptions' => [
            'class' => 'faa-parent animated-hover',
        ],
    ];
    $languageItems = array();
    foreach (Yii::$app->params['languages'] as $language => $details) {
        $languageItems[] = [
            'label' => '<span class="' . $details['icon'] . '"></span>' . \common\models\Translations::translate('app', $language), 'url' => $details['url']
        ];
    }
    $menuItems[] = [
        'label' => '<span class="fa fa-language">',
        'items' => $languageItems,
    ];
    $this->registerJs("
                    $('#nav-login').on('click', function (e) {
                        var loginNotice = new PNotify({
                            text: '" . preg_replace('#\s+#', ' ', trim($this->render('/site/modal-login', ['model' => new \common\models\LoginForm()]))) . "',
                            icon: false,
//                            width: 'auto',
                            hide: false,
                            buttons: {
                                closer: false,
                                sticker: false
                            },
//                            stack: {
//                                'dir1': 'down',
//                                'dir2': 'right',
//                                'modal': true
//                            },
//                            addclass: 'stack-modal custom-login',
                            addclass: 'custom-login',
                            animate: {
                                animate: true,
                                in_class: '" . \common\models\Actions::getNotificationAnimation('in') . "',
                                out_class: '" . \common\models\Actions::getNotificationAnimation('out') . "'
                            }
                        });
                        $('#nav-login, #nav-signup').on('click', function (e) {
                            loginNotice.remove();
                        });
                        $('ul.auth-clients > li > a').on('click', function (e) {
                            $('body .loading').addClass('active');
                            loginNotice.remove();
                        });
                        loginNotice.get().find('form#login-form').on('click', '[name=cancel-button]', function() {
                            loginNotice.remove();
                        }).submit(function() {
                            $('body .loading').addClass('active');
                            var formData = $(this).serializeArray().reduce(function(obj, item) {
                                        obj[item.name] = item.value;
                                        return obj;
                            }, {});
                            $.ajax({
                               url:'" . \yii\helpers\Url::to(['/login']) . "',
                               type: 'POST',
                               data: {
                                   'LoginForm': formData,
                               },
                               success: function(response) {
                                    result = JSON.parse(response);
                                    if (result != null) {
                                        $('body .loading').removeClass('active');
                                        loginNotice.attention('" . \common\models\Actions::getNotificationAnimation('attention') . "');
                                        for (var k in $('.help-block')) {
                                            $('.help-block')[k].innerText = '';
                                        }
                                        for (var k in result) {
                                            $('.field-loginform-'+k+' .help-block')[0].innerText = result[k];
                                        }
//                                        $('.field-loginform-rememberme .help-block')[0].innerText = result.password;
                                        return false;
                                    } else {
                                        location.reload(); 
                                    }
                               },
                               error: function (request, status, error) {
                                    window.alert(error);
                               }
                           }); 
                           return false;
                        });
                    });
                    
                    $('#nav-signup').on('click', function (e) {
                        var signupNotice = new PNotify({
                            text: '" . preg_replace('#\s+#', ' ', trim($this->render('/site/modal-signup', ['model' => new \frontend\models\SignupForm()]))) . "',
                            icon: false,
//                            width: 'auto',
                            hide: false,
                            buttons: {
                                closer: false,
                                sticker: false
                            },
//                            stack: {
//                                'dir1': 'down',
//                                'dir2': 'right',
//                                'modal': true
//                            },
//                            addclass: 'stack-modal custom-login',
                            addclass: 'custom-login',
                            animate: {
                                animate: true,
                                in_class: '" . \common\models\Actions::getNotificationAnimation('in') . "',
                                out_class: '" . \common\models\Actions::getNotificationAnimation('out') . "'
                            }
                        });
                        $('#nav-login, #nav-signup').on('click', function (e) {
                            signupNotice.remove();
                        });
                        $('ul.auth-clients > li > a').on('click', function (e) {
                            $('body .loading').addClass('active');
                            signupNotice.remove();
                        });
                        signupNotice.get().find('form#signup-form').on('click', '[name=cancel-button]', function() {
                            signupNotice.remove();
                        }).submit(function() {
                            $('body .loading').addClass('active');
                            var formData = $(this).serializeArray().reduce(function(obj, item) {
                                        obj[item.name] = item.value;
                                        return obj;
                            }, {});
                            $.ajax({
                               url:'" . \yii\helpers\Url::to(['/signup']) . "',
                               type: 'POST',
                               data: {
                                   'SignupForm': formData,
                               },
                               success: function(response) {
                                    result = JSON.parse(response);
                                    if (result != null) {
                                        $('body .loading').removeClass('active');
                                        signupNotice.attention('" . \common\models\Actions::getNotificationAnimation('attention') . "');
                                        for (var k in $('.help-block')) {
                                            $('.help-block')[k].innerText = '';
                                        }
                                        for (var k in result) {
                                            $('.field-signupform-'+k+' .help-block')[0].innerText = result[k];
                                        }
                                        return false;
                                    } else {
                                        location.reload(); 
                                    }
                               },
                               error: function (request, status, error) {
                                    window.alert(error);
                               }
                           }); 
                           return false;
                        });
                    });
                ", \yii\web\View::POS_END);
} else {
    $profile = \common\models\Profiles::findOne(Yii::$app->user->identity->id);
    $avatar = $profile->avatar ? \yii\helpers\Url::base() . '/' . $profile->avatar : '';
    $menuItems[] = [
        'label' => \yii\bootstrap\Html::img($avatar,
                [
                    'alt' => '',
                    'class' => 'navbar-avatar'
                ]) . ' <strong id="user_name">' . $profile->firstname . '</strong>',
        'url' => ['/profiles/my-profile']
    ];
    $menuItems[] = [
        'label' => '<i class="fa fa-tv faa-tada"></i>',
        'url' => ['/movies/index'],
        'linkOptions' => [
            'class' => 'faa-parent animated-hover',
        ]
    ];
    $menuItems[] = [
        'label' => '<i class="fa fa-comments faa-shake"></i>',
        'url' => ['/chat/index'],
        'linkOptions' => [
            'class' => 'faa-parent animated-hover',
        ],
        'options' => [
            'id' => 'chat-button'
        ]
    ];
    $languageItems = array();
    foreach (Yii::$app->params['languages'] as $language => $details) {
        $languageItems[] = [
            'label' => '<span class="' . $details['icon'] . '"></span>' . \common\models\Translations::translate('app', $language), 'url' => $details['url']
        ];
    }
    $menuItems[] = [
        'label' => '<span class="fa fa-language">',
        'items' => $languageItems,
    ];
    $friendships = \common\models\Friendship::getFriendRequestsForCurrent();
    $items = array();
    if (empty($friendships['Requests'])) {
        $items[] = ['label' => \common\models\Translations::translate('app', 'No friend requests.'), 'url' => '#', 'options' => ['class' => 'friend-request text-center no-padding']];
    } else {
        foreach ($friendships['Requests'] as $friendRequest) {
            /* @var $friendRequest \common\models\Friendship */
            $items[] = ['label' => "<img src='/" . $friendRequest->getUserFrom()->one()->getProfile()->one()->avatar . "' alt='' data-url='" . \common\models\Profiles::getProfileLinkByUserID($friendRequest->user_from) . "'>" . $friendRequest->getUserFrom()->one()->getFullName() . "<span class='fa fa-check-circle-o fa-2x' data-user_id='" . $friendRequest->user_from . "' onclick='return acceptFriendAJAX(this);'></span> <span class='fa fa-times-circle-o fa-2x' data-user_id='" . $friendRequest->user_from . "' onclick='return refuseFriendAJAX(this);'></span>", 'url' => '#', 'options' => ['class' => 'friend-request']];
        }
    }
    if (!empty($friendships['Requested'])) {
        $items[] = '<li class="dropdown-header">Requested:</li>';
    }
    foreach ($friendships['Requested'] as $friendRequest) {
        /* @var $friendRequest \common\models\Friendship */
        $items[] = ['label' => "<img src='/" . $friendRequest->getUserTo()->one()->getProfile()->one()->avatar . "' alt='' data-url='" . \common\models\Profiles::getProfileLinkByUserID($friendRequest->user_to) . "'>" . $friendRequest->getUserTo()->one()->getFullName() . "<span class='fa fa-times-circle-o fa-2x' data-user_id='" . $friendRequest->user_to . "' onclick='return removeFriendAJAX(this);'></span>", 'url' => '#', 'options' => ['class' => 'friend-request']];
    }
    if (empty($friendships['Requests'])) {
        $menuItems[] = [
            'label' => '<span class="fa fa-user-circle-o"></span><span class="badge badge-notify hidden">0</span>',
            'items' => $items,
            'options' => [
                'id' => 'friend-requests-dropdown'
            ]
        ];
    } else {
        $menuItems[] = [
            'label' => '<span class="fa fa-user-circle-o faa-pulse animated"></span><span class="badge badge-notify">' . count($friendships['Requests']) . '</span>',
            'items' => $items,
            'options' => [
                'id' => 'friend-requests-dropdown'
            ]
        ];
    }
    $notifications = \common\models\Notifications::find()->where(['user_id' => Yii::$app->user->identity->id])->orderBy(['created_at' => SORT_DESC])->all();
    $notificationItems = array();
    $notificationsCount = count($notifications);
    foreach ($notifications as $notification) {
        $notificationUser = \common\models\User::find()->where(['id' => $notification->getModel()->user_id])->one();
        if ($notificationUser->id != Yii::$app->user->identity->id) {
            $notificationItems[] = [
                'label' => "<img src='/" . $notificationUser->profile->avatar . "' alt='' data-url='" . \common\models\Profiles::getProfileLinkByUserID($notificationUser->id) . "'>" . $notification->description . "<span>" . date('d m Y H:i', $notification->created_at) . "</span>",
                'url' => '#',
                'options' => ['class' => 'notification ' . ($notification->read ? '' : 'active')],
            ];
        } else {
            $notificationsCount--;
        }
    }
    if ($notificationsCount) {
        // has notifications
        $readNotificationsCount = \common\models\Notifications::find()->where(['user_id' => Yii::$app->user->identity->id, 'read' => false])->count();
        $menuItems[] = [
            'label' => '<span class="fa fa-bell faa-ring ' . ($readNotificationsCount ? 'animated' : '') . '"></span><span class="badge badge-notify ' . ($readNotificationsCount ? '' : 'hidden') . '">' . $readNotificationsCount . '</span>',
            'items' => $notificationItems,
            'options' => [
                'id' => 'notifications-dropdown'
            ]
        ];
    } else {
        // doesn't have notifications
        $menuItems[] = [
            'label' => '<span class="fa fa-bell"></span><span class="badge badge-notify hidden">0</span>',
            'items' => [
                [
                    'label' => \common\models\Translations::translate('app', 'No notifications.'),
                    'url' => '#',
                    'options' => ['class' => 'notification text-center no-padding']
                ],
            ],
            'options' => [
                'id' => 'notifications-dropdown'
            ]
        ];
    }
    $menuItems[] = [
        'label' => '<i class="fa fa-power-off faa-pulse"></i>',
        'url' => ['/site/logout'],
        'linkOptions' => [
            'data-method' => 'post',
            'class' => 'faa-parent animated-hover',
            'onclick' => "$('body .loading').addClass('active');"
        ],
    ];
}
echo \yii\bootstrap\Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $menuItems,
    'encodeLabels' => false,
]);
\yii\bootstrap\NavBar::end();
?>