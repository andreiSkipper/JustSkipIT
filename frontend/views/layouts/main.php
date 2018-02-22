<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use common\models\Profiles;
use yii\helpers\Url;
use common\models\Actions;
use yii\web\View;
use common\models\LoginForm;
use frontend\models\SignupForm;
use common\models\Translations;

AppAsset::register($this);
$this->title = "JustSkipIT | " . $this->title;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/Icons/bomb-explosion-1.ico">
    <!--    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">-->
    <!--    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>-->
    <!--    <script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>-->

    <link rel="stylesheet" href="/js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.css">
    <script src="/js/jquery-1.11.3.min.js"></script>
    <!--    <script src="/js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.js"></script>-->

    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
//        'brandLabel' => 'Skipping IT',
        'brandLabel' => Html::img('@web/uploads/logos/JustSkipIT_logo1.png', ['alt' => Yii::$app->name, 'class' => 'navbar-logo']),
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
                'label' => '<span class="' . $details['icon'] . '"></span>' . Translations::translate('app', $language), 'url' => $details['url']
            ];
        }
        $menuItems[] = [
            'label' => '<span class="fa fa-language">',
            'items' => $languageItems,
        ];
        $this->registerJs("
                    $('#nav-login').on('click', function (e) {
                        var loginNotice = new PNotify({
                            text: '" . preg_replace('#\s+#', ' ', trim($this->render('/site/modal-login', ['model' => new LoginForm()]))) . "',
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
                                in_class: '" . Actions::getNotificationAnimation('in') . "',
                                out_class: '" . Actions::getNotificationAnimation('out') . "'
                            }
                        });
                        loginNotice.get().find('form#login-form').on('click', '[name=cancel-button]', function() {
                            loginNotice.remove();
                        }).submit(function() {
                            var formData = $(this).serializeArray().reduce(function(obj, item) {
                                        obj[item.name] = item.value;
                                        return obj;
                            }, {});
                            $.ajax({
                               url:'" . Url::to(['/login']) . "',
                               type: 'POST',
                               data: {
                                   'LoginForm': formData,
                               },
                               success: function(response) {
                                    result = JSON.parse(response);
                                    if (result != null) {
                                        loginNotice.attention('" . Actions::getNotificationAnimation('attention') . "');
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
                            text: '" . preg_replace('#\s+#', ' ', trim($this->render('/site/modal-signup', ['model' => new SignupForm()]))) . "',
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
                                in_class: '" . Actions::getNotificationAnimation('in') . "',
                                out_class: '" . Actions::getNotificationAnimation('out') . "'
                            }
                        });
                        signupNotice.get().find('form#signup-form').on('click', '[name=cancel-button]', function() {
                            signupNotice.remove();
                        }).submit(function() {
                            var formData = $(this).serializeArray().reduce(function(obj, item) {
                                        obj[item.name] = item.value;
                                        return obj;
                            }, {});
                            $.ajax({
                               url:'" . Url::to(['/signup']) . "',
                               type: 'POST',
                               data: {
                                   'SignupForm': formData,
                               },
                               success: function(response) {
                                    result = JSON.parse(response);
                                    if (result != null) {
                                        signupNotice.attention('" . Actions::getNotificationAnimation('attention') . "');
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
                ", View::POS_END);
    } else {
        $profile = Profiles::findOne(Yii::$app->user->identity->id);
        $avatar = $profile->avatar ? Url::base() . '/' . $profile->avatar : '';
        $menuItems[] = [
            'label' => Html::img($avatar,
                    [
                        'alt' => '',
                        'class' => 'navbar-avatar'
                    ]) . ' <strong>' . $profile->firstname . '</strong>',
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
            ]
        ];
        $languageItems = array();
        foreach (Yii::$app->params['languages'] as $language => $details) {
            $languageItems[] = [
                'label' => '<span class="' . $details['icon'] . '"></span>' . Translations::translate('app', $language), 'url' => $details['url']
            ];
        }
        $menuItems[] = [
            'label' => '<span class="fa fa-language">',
            'items' => $languageItems,
        ];
        $menuItems[] = [
            'label' => '<span class="fa fa-bell faa-ring animated"></span><span class="badge badge-notify">7</span>',
            'items' => [
                ['label' => 'Notification 1', 'url' => '#'],
                ['label' => 'Notification 2', 'url' => '#'],
                ['label' => 'Notification 3', 'url' => '#'],
                ['label' => 'Notification 4', 'url' => '#'],
            ],
        ];
        $menuItems[] = [
            'label' => '<i class="fa fa-power-off faa-pulse"></i>',
            'url' => ['/site/logout'],
            'linkOptions' => [
                'data-method' => 'post',
                'class' => 'faa-parent animated-hover',
            ]
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
        'encodeLabels' => false,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>


        <?php if (count(Yii::$app->session->getAllFlashes()) != 0) {
            foreach (Yii::$app->session->getAllFlashes() as $type => $notification) {
                $this->registerJs("
                    new PNotify({
                        title: '" . $notification['title'] . "',
                        text: '" . $notification['text'] . "',
                        type: '" . $type . "',
//                        icon: '" . $notification['icon'] . "',
                        buttons: {
                            classes: {
                                closer: 'fa fa-times-circle-o',
                                pin_up: 'fa fa-pause-circle-o',
                                pin_down: 'fa fa-play-circle-o'
                            },
                        },
                        animate: {
                            animate: true,
                            in_class: '" . Actions::getNotificationAnimation('in') . "',
                            out_class: '" . Actions::getNotificationAnimation('out') . "'
                        }
                    });
                ", View::POS_END);
            }
        } ?>
        <!--        --><?php //echo Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<?php
$class = '';
if (Yii::$app->controller->action->id == 'login' OR Yii::$app->controller->action->id == 'signup') {
    $class = 'login-footer';
}
?>

<footer class="footer <?= $class ?>">
    <div class="container">
        <p class="pull-left">
            <i class="fa fa-copyright"></i>
            DARK <?= date('Y') ?>
        </p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
