<?php

//use yii\helpers\Html;
use kartik\helpers\Html;
use yii\helpers\Url;
use common\models\Profiles;
use common\models\Actions;
use common\models\User;
use common\models\City;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use common\models\Translations;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MoviesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $profile Profiles */
/* @var $actions Actions */
/* @var $pages string */
/* @var $cities City */

$this->title = $profile->firstname . ' ' . $profile->lastname;
//$this->params['breadcrumbs'][] = $this->title;
$currentUser = User::findOne(Yii::$app->user->getId());
?>

<div class="col-xs-12 no-padding profile-heading">
    <?php
    if (!empty($profile->cover)) {
        $action = Actions::findOne(['imagePath' => $profile->cover]);
        echo $this->render('/actions/modal', ['action' => $action]);
    }
    ?>
    <img src="<?= Url::base() . '/' . $profile->cover; ?>" class="profile-cover" alt=""
        <?php if ($profile->cover) { ?>
            onclick="$('#action-modal-<?= $action->id ?>').modal()"
        <?php } ?>
    >

    <?php
    if ($profile->avatar) {
        $action = Actions::findOne(['imagePath' => $profile->avatar]);
        echo $this->render('/actions/modal', ['action' => $action]);
        ?>
        <img src="<?= Url::base() . '/' . $profile->avatar; ?>" class="profile-avatar" alt=""
             onclick="$('#action-modal-<?= $action->id ?>').modal()">
    <?php } ?>

    <div class="profile-name">
        <?php
        echo $profile->firstname . ' ' . $profile->lastname;
        if ($profile->nickname) {
            echo ' (' . $profile->nickname . ')';
        }
        ?>
    </div>

    <?php
    if (!Yii::$app->user->isGuest) {
        $url = explode('/', Url::current());
        $shortUrl = explode('?', $url[count($url) - 1])[0];
        $currentProfile = Profiles::findOne(Yii::$app->user->identity->getId());
        if ($shortUrl == $currentProfile->shortUrl OR $shortUrl == $currentUser->username OR $shortUrl == 'profile') {
            echo $this->render('/profiles/modal');
            ?>
            <?= Html::button('<i class="fa fa-pencil-square-o" style="font-size: 20px;"></i> ' . Translations::translate('app', 'Edit profile'),
                [
                    'class' => 'btn col-xs-12 profile-button',
                    'onclick' => "$('#profile-modal-" . $profile->user_id . "').modal()"
                ]) ?>
            <?php
        } else {
            $friendship = \common\models\Friendship::searchForFriendship($profile->user_id);
            if (empty($friendship)) {
                echo Html::button('<i class="fa fa-user-plus" style="font-size: 20px;"></i> ' . Translations::translate('app', 'Add Friend'),
                    [
                        'class' => 'btn col-xs-12 profile-button',
                        'data-user_id' => $profile->user_id,
                        'id' => 'add-friend',
                        'onclick' => "return addFriendAJAX(this);",
                    ]);
            } else {
                switch ($friendship->status) {
                    case \common\models\Friendship::STATUS_REQUESTED:
                        echo Html::button('<i class="fa fa-remove" style="font-size: 20px;"></i> ' . Translations::translate('app', 'Remove Friend Request'),
                            [
                                'class' => 'btn col-xs-12 profile-button',
                                'data-user_id' => $profile->user_id,
                                'id' => 'remove-request',
                                'onclick' => "return removeFriendAJAX(this);",
                            ]);
                        break;
                    case \common\models\Friendship::STATUS_ACCEPTED:
                        echo Html::button('<i class="fa fa-remove" style="font-size: 20px;"></i> ' . Translations::translate('app', 'Remove Friend'),
                            [
                                'class' => 'btn col-xs-12 profile-button',
                                'data-user_id' => $profile->user_id,
                                'id' => 'remove-friend',
                                'onclick' => "return removeFriendAJAX(this);",
                            ]);
                        break;
                    case \common\models\Friendship::STATUS_REFUSED:
                        echo Html::button('<i class="fa fa-user-plus" style="font-size: 20px;"></i> ' . Translations::translate('app', 'Re-send Friend Request'),
                            [
                                'class' => 'btn col-xs-12 profile-button',
                                'data-user_id' => $profile->user_id,
                                'id' => 'add-friend',
                                'onclick' => "return addFriendAJAX(this);",
                            ]);
                        break;
                }
            }
            ?>
            <?php
        }
    }
    ?>
</div>

<div class="clearfix"></div>

<div class="row">
    <div id="profile-sidebar">
        <div class="col-md-4 col-xs-12">
            <?php if (
                $profile->phoneNumber OR $profile->currentCity OR $profile->birthCity OR $profile->birthday OR
                $profile->sex OR $profile->interestedIn OR $profile->relationship OR $profile->work
            ) { ?>
                <div class="panel panel-default text-center">
                    <div class="panel-heading">
                        <?= Translations::translate('app', 'About') ?>
                    </div>
                    <div class="panel-body text-center">
                        <?php if ($profile->work) { ?>
                            <div class="col-xs-6">
                                <i class="fa fa-suitcase"></i>
                                <?= $profile->work ?>
                            </div>
                        <?php } ?>
                        <?php if ($profile->knownLanguages) { ?>
                            <div class="col-xs-6">
                                <i class="fa fa-language"></i>
                                <?= Translations::translate('app', $profile->knownLanguages) ?>
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
                                <?= Translations::translate('app', $profile->sexEnum[$profile->sex]) ?>
                            </div>
                        <?php } ?>
                        <?php if ($profile->interestedIn) { ?>
                            <div class="col-xs-6">
                                <i class="fa fa-hand-o-right"></i>
                                <?= Translations::translate('app', $profile->sexEnum[$profile->interestedIn]) ?>
                            </div>
                        <?php } ?>
                    </div>
                    <?php if ($profile->relationship) { ?>
                        <div class="panel-footer">
                            <i class="fa fa-heartbeat fa-2x"></i>
                            <?= Translations::translate('app', $profile->relationshipEnum[$profile->relationship]) ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="clearfix hidden-md hidden-lg"></div>

    <div class="col-md-8" id="profile-activity">
        <?php if (!Yii::$app->user->isGuest AND Yii::$app->user->id == $profile->user_id) { ?>
            <?php $form = ActiveForm::begin(['action' => ['/add-post'], 'options' => ['enctype' => 'multipart/form-data']]); ?>
            <div class="panel panel-default action-new">
                <!--            <div class="panel-heading action-header"></div>-->
                <div class="panel-body ">
                    <div class="col-xs-12">
                        <?php
                        $newAction = new Actions();
                        $newAction->privacy = 'Public';
                        ?>
                        <?= $form->field($newAction, 'description')->textarea(
                            [
                                'maxlength' => true,
                                'placeholder' => Translations::translate('app', 'What are you thinking about?'),
                                'style' => 'resize: vertical;min-height: 50px'
                            ])->label(false);
                        ?>
                    </div>
                    <div class="col-xs-12">
                        <?= $form->field($newAction, 'imagePath')->widget(FileInput::classname(), [
                            'options' => [
                                'accept' => 'image/*',
                                'multiple' => true
                            ],
                            'pluginOptions' => [
                                'showPreview' => true,
                                'showCaption' => true,
                                'showRemove' => true,
                                'showUpload' => false,
                                'browseLabel' => Translations::translate('app', 'Browse') . ' ...',
                                'maxFileSize' => 5000
                            ]
                        ])->label(false) ?>
                    </div>
                    <div class="col-xs-12 no-padding">
                        <div class="col-xs-6">
                            <?= $form->field($newAction, 'privacy')->widget(Select2::classname(),
                                [
                                    'name' => 'Actions[type]',
                                    'data' => $newAction->getPrivacies(),
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'hideSearch' => true,
                                    'options' => [
                                        'placeholder' => Translations::translate('app', 'Privacy'),
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => false
                                    ],
                                    'addon' => [
                                        'prepend' => [
                                            'content' => Html::icon('globe')
                                        ],
                                    ]
                                ])->label(false) ?>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <?= Html::submitButton('<i class="fa fa-pencil-square-o"></i> ' . Translations::translate('app', 'Post'),
                                    [
                                        'class' => 'btn btn-orange col-xs-12',
                                        'name' => 'action-edit-button',
                                        'onclick' => "$('body .loading').addClass('active');",
                                    ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        <?php } ?>
        <div id="actions-group">
            <?php
            foreach ($actions as $action) {
                echo $this->render('/actions/panel', ['action' => $action]);
            } ?>
        </div>
        <p id="loading" class="text-center" data-offset="<?= count($actions) ?>" data-total="<?= $pages->totalCount ?>"
           style="display: none;">
            <span class="fa fa-spinner fa-pulse fa-4x"></span>
        </p>
    </div>
</div>

<script>
    $(document).ready(function () {
        var win = $(window);

        // Each time the user scrolls
        win.scroll(function () {
            // End of the document reached?
            var loading = $('#loading');
            if ($(document).height() - win.height() === win.scrollTop() && !loading.is(":visible")) {
                var offset = loading.data('offset');
                var total = loading.data('total');

                if (offset < total) {
                    loading.show();
                    $.ajax({
                        url: '',
                        type: 'POST',
                        data: {
                            offset: offset
                        },
                        success: function (response) {
                            result = JSON.parse(response);
                            if (result.html.length !== 0) {
                                $("#actions-group").append(result.html).fadeIn(10000);
                                loading.data('offset', offset + result.actions);
                            }
                            loading.hide();
                        },
                        error: function (request, status, error) {
                            window.alert(error);
                        }
                    });
                }
            }
        });
    });

    var elementPosition = $('#profile-sidebar').offset();

    $(window).scroll(function () {
        if ($(window).scrollTop() > elementPosition.top - 70) {
            $('#profile-sidebar').css('position', 'fixed').css('top', '70px').addClass("container no-padding");
            $('#profile-activity').addClass('col-md-offset-4');
        } else {
            $('#profile-sidebar').css('position', 'static').removeClass("container no-padding");
            $('#profile-activity').removeClass('col-md-offset-4');
        }
    });
</script>
