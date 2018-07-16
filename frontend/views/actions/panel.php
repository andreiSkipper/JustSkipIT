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
                try {
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
                                    'label' => \common\models\Translations::translate('app', 'Delete'),
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
                                                                socket.emit('remove-comment', {
                                                                    element: '#action-" . $action->id . "'
                                                                });
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
                                    'label' => \common\models\Translations::translate('app', 'Edit'),
                                    'url' => '#',
                                    'options' => [
                                        'id' => 'edit-' . $action->id,
                                        'onclick' => "event.preventDefault();$('#action-modal-" . $action->id . "').modal();",
                                    ],
                                ],
                                [
                                    'label' => \common\models\Translations::translate('app', 'View'),
                                    'url' => '/post/' . $action->id,
                                    'options' => [
                                        'id' => 'view-' . $action->id
                                    ],
                                ],
                            ],
                        ],
                    ]);
                } catch (Exception $e) {
                    var_dump($e->getMessage());
                }
            } else {
                try {
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
                                    'label' => 'View',
                                    'url' => '/post/' . $action->id,
                                    'options' => [
                                        'id' => 'view-' . $action->id
                                    ],
                                ],
                            ],
                        ],
                    ]);
                } catch (Exception $e) {
                    var_dump($e->getMessage());
                }
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
                <div class="clearfix"></div>
                <div class="social-share">
                    <?= \common\models\Translations::translate('app', 'Share on:') ?>
                    <?= \ymaker\social\share\widgets\SocialShare::widget([
                        'configurator' => 'socialShare',
                        'url' => Url::to('/post/' . $action->id, true),
                        'title' => 'SkipIT.ro',
                        'description' => $profile->firstname . ' ' . $profile->lastname . ' ' . $action->getActionTextByType($action->type) . ' ' . date('d M Y H:i', $action->created_at),
                        'imageUrl' => $action->imagePath ? Url::to('/uploads/logos/JustSkipIT_logo1.png', true) : Url::to('/' . $action->imagePath, true),
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
                    ])->textInput(['placeholder' => \common\models\Translations::translate('app', "Click to add Comment..."), 'autocomplete' => "off"])->label(false) ?>
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
