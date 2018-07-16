<?php

use yii\helpers\Url;
use common\models\User;
use common\models\Comments;
use common\models\Profiles;
use common\models\Actions;
use kartik\form\ActiveForm;
use yii\web\View;
use common\models\Translations;

/* @var $this yii\web\View */
/* @var $comment Comments */
/* @var $user User */
/* @var $profile Profiles */
/* @var $modal null|boolean */

$user = $comment->user;
$profile = $user->profile;
$avatar = $profile->avatar ? Url::base() . '/' . $profile->avatar : '';
$modal = isset($modal) ? $modal : false;
$modalClass = empty($modal) ? '' : 'modal-';
?>
<div id="<?= $modalClass ?>comment-<?= $comment->id ?>">
    <div class="panel panel-default">
        <div class="panel-heading" id="comment-<?= $comment->id ?>-heading">
            <div id="comment-<?= $comment->id ?>-user" style="display: inline-block" class="action-tooltip">
                <div class="hidden action-tooltip-content">
                    <?= $this->render('/actions/user-heading', ['action' => $comment]) ?>
                </div>
                <a href="<?= Profiles::getProfileLinkByUserID($comment->user_id) ?>" class="action-link">
                    <img src="<?= $avatar; ?>" class="action-avatar" alt="">
                    <?= (isset($profile->lastname) AND isset($profile->firstname)) ? $profile->lastname . ' ' . $profile->firstname : $user->username ?>
                </a>
            </div>
            <?= \common\models\Translations::translate('app', 'added comment') ?>
            <?= date("d m Y H:i", $comment->updated_at) ?>
            <?php
            if ($comment->user_id == Yii::$app->user->getId()) { ?>
                <div class="comment-buttons pull-right"
                     style="<?= empty($profile->avatar) ? '' : 'line-height: 34px;' ?>">
                    <i id="edit-comment-<?= $comment->id ?>" class="fa fa-pencil" aria-hidden="true" title="Edit"
                       onclick="
                               $('#entitiescomments-id')[0].value = '<?= $comment->id ?>';
                               $('#edit-comment-text')[0].value = '<?= $comment->content ?>';
                               $('#entities-edit-comment').modal();
                               "></i>
                    <i id="delete-comment-<?= $comment->id ?>" class="fa fa-times" aria-hidden="true" title="Delete"
                       onclick="
                               event.preventDefault();
                               (new PNotify({
                               title: 'Confirmation Needed',
                               text: 'Are you sure you want to delete this comment?',
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
                               in_class: ' <?= Actions::getNotificationAnimation('in') ?>',
                               out_class: '<?= Actions::getNotificationAnimation('out') ?>'
                               }
                               })).get().on('pnotify.confirm', function() {
                               $.ajax({
                               url:'<?= Url::to(['/delete-comment', 'id' => $comment->id]) ?>',
                               type: 'GET',
                               success: function(response) {
                               new PNotify({
                               title: '<?= Translations::translate('app', 'Success') ?>',
                               text: '<?= Translations::translate('app', 'Comment has been deleted.') ?>',
                               type: 'success',
                               buttons: {
                               classes: {
                               closer: 'fa fa-times-circle-o',
                               pin_up: 'fa fa-pause-circle-o',
                               pin_down: 'fa fa-play-circle-o'
                               }
                               },
                               animate: {
                               animate: true,
                               in_class: '<?= Actions::getNotificationAnimation('in') ?>',
                               out_class: '<?= Actions::getNotificationAnimation('out') ?>'
                               }
                               });

                               socket.emit('remove-comment', {
                               element: '#comment-<?= $comment->id ?>',
                               modal_element: '#modal-comment-<?= $comment->id ?>'
                               });

                               socket.emit('notification', {
                               from: model_user.id,
                               to: parseInt(<?= $comment->action->user_id ?>)
                               });
                               },
                               error: function (request, status, error) {
                               window.alert(error);
                               }
                               });
                               });
                               "></i>
                </div>
            <?php } ?>
        </div>
        <div class="panel-body">
            <div class="col-xs-12 comment-text" id="comment-<?= $comment->id ?>-text">
                <div class="type-wrap">
                    <div class="typed-strings hidden">
                        <p>
                            <?= $comment->content ?>
                        </p>
                    </div>
                    <span class="typed"></span>
                </div>
<!--                --><?php //if ($comment->location) { ?>
<!--                    <div class="pull-right">-->
<!--                        --><?php //echo $comment->getLocation()->city . ', ' . $comment->getLocation()->country ?>
<!--                    </div>-->
<!--                --><?php //} ?>
            </div>
        </div>
    </div>
    <div class="comments-reply">
        <div id="<?= $modalClass ?>comments-reply-<?= $comment->id ?>">
            <?php
            foreach ($comment->replies as $reply) {
                echo $this->render('/comments/comment-details', ['comment' => $reply, 'modal' => $modal]);

                $this->registerJs("", View::POS_END);
            }
            ?>
        </div>
        <?php
        if (empty($comment->reply_id) AND !Yii::$app->user->isGuest) {
            $model = new Comments();
            $model->action_id = $comment->action_id;
            $model->reply_id = $comment->id;
            $form = ActiveForm::begin([
                'enableClientValidation' => false,
                'id' => 'add-comment-reply-form-' . $modalClass . $comment->id,
                'options' => [
                    'data-url' => Url::to(['/add-comment']),
                    'data-comment_id' => $comment->id,
                    'class' => 'add-comment-reply-form',
                    'onsubmit' => 'return addReplyAJAX(this);'
                ],
            ]); ?>

            <?= $form->field($model, 'action_id')->hiddenInput()->label(false) ?>

            <?= $form->field($model, 'reply_id')->hiddenInput()->label(false) ?>

            <?= $form->field($model, 'content', ['addon' => ['prepend' => ['content' => '<i class="fa fa-comments-o"></i>'],
                //                                    'append' => [
                //                                        'content' => Html::submitButton('Go', ['class' => 'btn btn-default', 'id' => 'add_comment']),
                //                                        'asButton' => true
                //                                    ]
            ]])->textInput(['placeholder' => \common\models\Translations::translate('app', "Click to add reply..."), 'autocomplete' => "off"])->label(false) ?>
            <?php
            ActiveForm::end();

            $this->registerJs("
                                    
                            ", View::POS_END);
        }
        ?>
    </div>
    <div class="clearfix"></div>
</div>
