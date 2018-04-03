<?php

/* @var $this yii\web\View */
/* @var $name string */

/* @var $action \common\models\Actions */

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Profiles;
use common\models\Actions;
use common\models\Comments;
use yii\bootstrap\Modal;
use yii\bootstrap\ButtonDropdown;
use yii\web\View;
use kartik\form\ActiveForm;

$profile = Profiles::findOne($action->user_id);
$avatar = $profile->avatar ? Url::base() . '/' . $profile->avatar : '';
?>

<div id="action-<?= $action->id ?>">
    <div class="clearfix"></div>
    <?= $this->render('/actions/modal', ['action' => $action]); ?>
    <div class="panel panel-default">
        <div class="panel-heading action-header">
            <div id="action-<?= $action->id ?>-user" style="display: inline-block"
                 class="action-tooltip"
            >
                <div class="hidden action-tooltip-content">
                    <?= $this->render('/actions/user-heading', ['action' => $action]); ?>
                </div>
                <a href="<?= Profiles::getProfileLinkByUserID($action->user_id) ?>" class="action-link">
                    <img src="<?= $avatar; ?>" class="action-avatar" alt="">
                </a>

                <a href="<?= Profiles::getProfileLinkByUserID($action->user_id) ?>" class="action-link">
                    <?= $profile->firstname . ' ' . $profile->lastname ?>
                </a>
            </div>

            <?= $action->getActionTextByType($action->type) ?>
            <?= date('d M Y H:i', $action->created_at); ?>
            <?php
            if (!Yii::$app->user->isGuest AND $action->user_id == Yii::$app->user->identity->getId()) {
                echo ButtonDropdown::widget([
                    'label' => '<span class="fa fa-ellipsis-h"></span>',
                    'encodeLabel' => false,
                    'containerOptions' => [
                        'class' => 'pull-right',
                    ],
                    'options' => [
                        'class' => 'action-drop-button',
                    ],
                    'dropdown' => [
                        'items' => [
                            [
                                'label' => 'Delete',
                                'url' => '#',
                                'options' => [
                                    'id' => 'delete-' . $action->id,
                                    'onclick' => "
                                                event.preventDefault();
                                                (new PNotify({
                                                    title: 'Confirmation Needed',
                                                    text: 'Are you sure you want to delete this post?',
                                                    icon: 'fa fa-question-circle faa-pulse animated',
                                                    hide: false,
                                                    confirm: {
                                                        confirm: true
                                                    },
                                                    buttons: {
                                                        closer: false,
                                                        sticker: false
                                                    },
                                                    history: {
                                                        history: false
                                                    },
                                                    addclass: 'stack-modal',
                                                    stack: {
                                                        'dir1': 'down',
                                                        'dir2': 'right',
                                                        'modal': true
                                                    },
                                                    animate: {
                                                        animate: true,
                                                        in_class: '" . Actions::getNotificationAnimation('in') . "',
                                                        out_class: '" . Actions::getNotificationAnimation('out') . "'
                                                    }
                                                })).get().on('pnotify.confirm', function() {
                                                       $.ajax({
                                                           url:'" . Url::to(['/delete-post', 'id' => $action->id]) . "',
                                                           type: 'GET',
                                                           success: function(response) {
                                                                $('#action-" . $action->id . "').animate({height: 'toggle'});
                                                           },
                                                           error: function (request, status, error) {
                                                                window.alert(error);
                                                           }
                                                       });
                                                });
                                ",
                                ],
                            ],
                            [
                                'label' => 'Edit',
                                'url' => '#',
                                'options' => [
                                    'id' => 'edit-' . $action->id,
                                    'onclick' => "event.preventDefault();$('#action-modal-" . $action->id . "').modal();",
                                ],
                            ],
                        ],
                    ],
                ]);
            } ?>
            <div class="clearfix"></div>
        </div>

        <?php if ($action->imagePath OR $action->description) { ?>
            <div class="panel-body">
                <?php if ($action->description) { ?>
                    <div class="col-xs-12 action-description">
                        <?= $action->description ?>
                    </div>
                <?php } ?>
                <div class="col-xs-12">
                    <img src="<?= Url::base() . '/' . $action->imagePath; ?>" class="action-photo" alt=""
                         onclick="$('#action-modal-<?= $action->id ?>').modal()">
                </div>
                <?php if (!Yii::$app->user->isGuest AND $action->user_id == Yii::$app->user->identity->getId()) { ?>
                    <!--                    <div class="col-xs-12 no-padding action-buttons">-->
                    <!--                        <div class="col-xs-6">-->
                    <!--                            <div class="form-group">-->
                    <!--                                --><?php //echo Html::button('<i class="fa fa-close"></i> Delete',
//                                    [
//                                        'class' => 'btn btn-danger col-xs-12',
//                                        'name' => 'action-edit-button',
//                                        'onclick' => "
//                                                if(confirm('Are you sure you want to delete this comment?'))
//                                                   {
//                                                       $.ajax({
//                                                           url:'" . Url::to(['/delete-post', 'id' => $action->id]) . "',
//                                                           type: 'GET',
//                                                           success: function(response) {
//                                                                $('#action-" . $action->id . "').animate({height: 'toggle'});
//                                                           },
//                                                           error: function (request, status, error) {
//                                                                window.alert(error);
//                                                           }
//                                                       });
//                                                   }
//                                        "
//                                    ]) ?>
                    <!--                            </div>-->
                    <!--                        </div>-->
                    <!--                        <div class="col-xs-6">-->
                    <!--                            <div class="form-group">-->
                    <!--                                --><?php //echo Html::button('<i class="fa fa-pencil-square-o"></i> Edit',
//                                    [
//                                        'class' => 'btn btn-orange col-xs-12',
//                                        'name' => 'action-edit-button',
//                                        'onclick' => "
//                                            $('#action-modal-" . $action->id . "').modal();
//                                        ",
//                                    ]) ?>
                    <!--                            </div>-->
                    <!--                        </div>-->
                    <!--                    </div>-->
                <?php } ?>
                <div class="clearfix"></div>
                <div class="social-share">
                    <?= \common\models\Translations::translate('app', 'Share on:') ?>
                    <?= \ymaker\social\share\widgets\SocialShare::widget([
                        'configurator' => 'socialShare',
                        'url' => \yii\helpers\Url::to('absolute/route/to/page', true),
                        'title' => 'Title of the page',
                        'description' => 'Description of the page...',
                        'imageUrl' => \yii\helpers\Url::to('absolute/route/to/image.png', true),
                    ]); ?>
                </div>
            </div>
        <?php } ?>
        <div class="panel-footer">
            <div class="action-comments">
                <div id="comments-<?= $action->id ?>">
                    <?php
                    /* @var $comments Comments[] */
                    $comments = Comments::find()->where(['action_id' => $action->id, 'reply_id' => null])->all();
                    foreach ($comments as $comment) {
                        echo $this->render('/comments/comment-details', ['comment' => $comment]);
                        ?>
                    <?php } ?>
                </div>
                <?php
                if (!Yii::$app->user->isGuest) {
                    $model = new Comments();
                    $model->action_id = $action->id;
                    $form = ActiveForm::begin([
                        'enableClientValidation' => false,
                        'id' => 'add-comment-form-' . $action->id,
                        'options' => [
                            'data-url' => Url::to(['/add-comment']),
                            'class' => 'add-comment-form',
                            'onsubmit' => 'return addCommentAJAX(this);'
                        ],
                    ]); ?>

                    <?= $form->field($model, 'action_id')->hiddenInput()->label(false) ?>

                    <?= $form->field($model, 'content', [
                        'addon' => [
                            'prepend' => ['content' => '<i class="fa fa-comment-o"></i>'],
//                        'append' => [
//                            'content' => Html::submitButton('Go', ['class' => 'btn btn-default', 'id' => 'add_comment']),
//                            'asButton' => true
//                        ]
                        ]
                    ])->textInput(['placeholder' => "Click to add Comment...", 'autocomplete' => "off"])->label(false) ?>
                    <?php
                    ActiveForm::end();
                }
                ?>
            </div>
        </div>
    </div>
</div>


<?php
$this->registerJs("
    
", View::POS_END);
?>
