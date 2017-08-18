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
            <a href="<?= Profiles::getProfileLinkByUserID($comment->user_id) ?>" class="action-link">
                <img src="<?= $avatar; ?>" class="action-avatar" alt="">
                <?= (isset($profile->lastname) AND isset($profile->firstname)) ? $profile->lastname . ' ' . $profile->firstname : $user->username ?>
            </a>
            added comment
            <?= date("Y-m-d H:i", $comment->updated_at) ?>
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
                               $('#comment-<?= $comment->id ?>').animate({height: 'toggle'});
                               $('#modal-comment-<?= $comment->id ?>').animate({height: 'toggle'});
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
            <div class="col-xs-12" id="comment-<?= $comment->id ?>-text">
                <?= $comment->content ?>
            </div>
        </div>
    </div>
    <div class="comments-reply">
        <div id="<?= $modalClass ?>comments-reply-<?= $comment->id ?>">
            <?php
            foreach ($comment->replies as $reply) {
                echo $this->render('/comments/comment-details', ['comment' => $reply, 'modal' => $modal]);
            }
            ?>
        </div>
        <?php
        if (empty($comment->reply_id)) {
            $model = new Comments();
            $model->action_id = $comment->action_id;
            $model->reply_id = $comment->id;
            $form = ActiveForm::begin([
                'enableClientValidation' => false,
                'id' => 'add-comment-reply-form-' . $modalClass . $comment->id
            ]); ?>

            <?= $form->field($model, 'action_id')->hiddenInput()->label(false) ?>

            <?= $form->field($model, 'reply_id')->hiddenInput()->label(false) ?>

            <?= $form->field($model, 'content', [
                'addon' => [
                    'prepend' => ['content' => '<i class="fa fa-comments-o"></i>'],
//                                    'append' => [
//                                        'content' => Html::submitButton('Go', ['class' => 'btn btn-default', 'id' => 'add_comment']),
//                                        'asButton' => true
//                                    ]
                ]
            ])->textInput(['placeholder' => "Click to add reply..."])->label(false) ?>
            <?php
            ActiveForm::end();

            $this->registerJs("
                                    $('#add-comment-reply-form-" . $modalClass . $comment->id . "').on('beforeSubmit', function (e) {
                                        e.preventDefault();
                                        if($(this).find('.has-error').length == 0) {
                                            var commentText = $('#add-comment-reply-form-" . $modalClass . $comment->id . " #comments-content');
                                            var commentActionID = $('#add-comment-reply-form-" . $modalClass . $comment->id . " #comments-action_id');
                                            var commentReplyID = " . $comment->id . ";
                                            if(commentText[0].value.length != 0){
                                                $.ajax({
                                                    url: '" . Url::to(['/add-comment']) . "',
                                                    type: 'POST',
                                                    data:{
                                                        'Comments':{
                                                            'content': commentText[0].value,
                                                            'action_id': commentActionID[0].value,
                                                            'reply_id': commentReplyID
                                                        }
                                                    },
                                                    success: function(response) {
                                                        result = JSON.parse(response);
                                                        if (result.html.length != 0) {
                                                            $('#modal-comments-reply-" . $comment->id . "').append(result.html).show('slow');
                                                            $('#comments-reply-" . $comment->id . "').append(result.html).show('slow');
                                                            document.getElementById('add-comment-reply-form-" . $modalClass . $comment->id . "').reset();
                                                        }
                                                    },
                                                    error: function (request, status, error) {
                                                        window.alert(error);
                                                    }
                                                });
                                            }
                                        }
                                        return false;
                                }).submit(function (e) {e.preventDefault();});
                            ", View::POS_END);
        }
        ?>
    </div>
    <div class="clearfix"></div>
</div>
