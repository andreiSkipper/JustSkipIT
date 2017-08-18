<?php
/**
 * Created by PhpStorm.
 * User: andrei
 * Date: 11/08/2017
 * Time: 12:37
 */
use common\models\Translations;
use common\models\User;
use common\models\Profiles;
use kartik\helpers\Html;

/* @var $user User */
$user = Yii::$app->user->identity;
/* @var $profile Profiles */
$profile = $user->profile;
?>

<h2>
    <?= Translations::translate('app', 'Hi') ?>
    <?= (empty($profile->firstname) AND empty($profile->lastname)) ? $user->username . ',' : $profile->firstname . ' ' . $profile->lastname . ',' ?>
</h2>

<p>
    <?= Translations::translate('app', 'Your account was created. Welcome to SkipIT!') ?>
</p>

<p>
    <?= Translations::translate('app', 'You can verify your account clicking') ?>
    <?= Html::a(Translations::translate('app', 'here'), ['/verify', 'auth_key' => $user->auth_key]) ?>.
</p>
