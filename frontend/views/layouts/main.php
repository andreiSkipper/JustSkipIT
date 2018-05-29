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
    <!--    <meta name="viewport" content="width=device-width, initial-scale=1">-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="icon" href="/Icons/bomb-explosion-1.ico">
    <!--    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">-->
    <!--    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>-->
    <!--    <script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>-->

    <link rel="stylesheet" href="/js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.css">
    <script src="/js/jquery-1.11.3.min.js"></script>

    <script src='/js/socket.io.js'></script>
    <script>
        // var socket = io('http://ec2-35-159-26-29.eu-central-1.compute.amazonaws.com:3002');
        var socket = io('http://localhost:3002');

        <?php if (!Yii::$app->user->isGuest) { ?>
        var guest = false;
        var model_user = <?= json_encode(\common\models\User::find()->where(['id' => Yii::$app->user->identity->id])->one()->toArray()) ?>;
        var model_profile = <?= json_encode(\common\models\Profiles::find()->where(['user_id' => Yii::$app->user->identity->id])->one()->toArray()) ?>;
        <?php } else { ?>
        var guest = true;
        <?php } ?>
    </script>


    <script src="/js/app.js"></script>
    <script src="/js/typed-js/typed.min.js"></script>
    <!--    <script src="/js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.js"></script>-->

    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<div class="loading">
    <div class="loading-wheel"></div>
</div>

<?php $this->beginBody() ?>


<div class="wrap">
    <?= $this->render('navbar_header') ?>

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
