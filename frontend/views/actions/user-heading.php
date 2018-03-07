<?php
/**
 * Created by PhpStorm.
 * User: andrei
 * Date: 08/08/2017
 * Time: 11:45
 */

/* @var $action \common\models\Actions */

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\models\Profiles;
use common\models\Translations;
use common\models\City;

$profile = Profiles::findOne($action->user_id);
$cities = ArrayHelper::map(City::find()->where(['CountryCode' => 'ROM'])->orderBy('Name')->all(), 'ID', 'Name');
?>
<div class="action-user-heading">
    <div class="panel panel-default text-center" style="color: black">
        <div class="panel-body text-center no-padding">

            <div class="col-xs-12 no-padding profile-heading">
                <?php if ($profile->cover) { ?>
                    <img src="<?= Url::base() . '/' . $profile->cover; ?>" class="profile-cover" alt="">
                <?php } ?>
            </div>
            <div class="col-xs-12 no-padding"
                 style="<?= $profile->cover ? $profile->avatar ? 'margin-top: -70px;' : 'margin-top: -30px;' : 'margin-top: 20px;' ?>">
                <?php
                if ($profile->avatar) { ?>
                    <img src="<?= Url::base() . '/' . $profile->avatar; ?>" class="profile-avatar" alt="">
                <?php } ?>
                <?php if ($profile->work) { ?>
                    <div class="col-xs-6">
                        <i class="fa fa-suitcase"></i>
                        <?= $profile->work ?>
                    </div>
                <?php } ?>
                <?php if ($profile->knownLanguages) { ?>
                    <div class="col-xs-6">
                        <i class="fa fa-language"></i>
                        <?= $profile->knownLanguages ?>
                    </div>
                <?php } ?>
                <?php if ($profile->birthday) { ?>
                    <div class="col-xs-6">
                        <i class="fa fa-birthday-cake"></i>
                        <?= date('d-m-Y', strtotime($profile->birthday)) ?>
                    </div>
                <?php } ?>
                <?php if ($profile->phoneNumber) { ?>
                    <div class="col-xs-6">
                        <i class="fa fa-mobile"></i>
                        <?= $profile->phoneNumber ?>
                    </div>
                <?php } ?>
                <?php if ($profile->currentCity) { ?>
                    <div class="col-xs-6">
                        <i class="fa fa-globe"></i>
                        <?= Translations::translate('app', 'Living in') ?>:
                        <?= $cities[$profile->currentCity] ?>
                    </div>
                <?php } ?>
                <?php if ($profile->birthCity) { ?>
                    <div class="col-xs-6">
                        <i class="fa fa-globe"></i>
                        <?= Translations::translate('app', 'From') ?>:
                        <?= $cities[$profile->birthCity] ?>
                    </div>
                <?php } ?>
                <?php if ($profile->sex) { ?>
                    <div class="col-xs-6">
                        <i class="fa fa-transgender"></i>
                        <?= $profile->sexEnum[$profile->sex] ?>
                    </div>
                <?php } ?>
                <?php if ($profile->interestedIn) { ?>
                    <div class="col-xs-6">
                        <i class="fa fa-hand-o-right"></i>
                        <?= $profile->sexEnum[$profile->interestedIn] ?>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php if ($profile->relationship) { ?>
            <div class="panel-footer">
                <i class="fa fa-heartbeat fa-2x">
                    <?= Translations::translate('app', $profile->relationshipEnum[$profile->relationship]) ?>
                </i>
            </div>
        <?php } ?>
    </div>
</div>

