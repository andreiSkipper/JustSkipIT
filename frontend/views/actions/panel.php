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
                 onmouseover="tooltip<?= $action->id ?>.open();"
                 onmousemove="tooltip<?= $action->id ?>.get().css({'top': event.clientY+12, 'left': event.clientX+12});"
                 onmouseout="tooltip<?= $action->id ?>.remove();"
            >
                <a href="<?= Profiles::getProfileLinkByUserID($action->user_id) ?>" class="action-link">
                    <img src="<?= $avatar; ?>" class="action-avatar" alt="">
                </a>

                <a href="<?= Profiles::getProfileLinkByUserID($action->user_id) ?>" class="action-link">
                    <?= $profile->firstname . ' ' . $profile->lastname ?>
                </a>
            </div>

            <?= $action->getActionTextByType($action->type) ?>
            <?= date('d M Y H:i:s', $action->created_at); ?>
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
                $model = new Comments();
                $model->action_id = $action->id;
                $form = ActiveForm::begin([
                    'enableClientValidation' => false,
                    'id' => 'add-comment-form-' . $action->id
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
                ])->textInput(['placeholder' => "Click to add Comment..."])->label(false) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>


<?php
$this->registerJs("
    tooltip" . $action->id . " = new PNotify({
        text: '" . preg_replace('#\s+#', ' ', trim($this->render('/actions/user-heading', ['action' => $action]))) . "',
        hide: false,
        width: '30%',
        addclass: 'custom-action-user',
        buttons: {
            closer: false,
            sticker: false
        },
        history: {
            history: false
        },
        animate_speed: \"fast\",
        icon: '',
        // Setting stack to false causes PNotify to ignore this notice when positioning.
        stack: false,
        auto_display: false
    });
    // Remove the notice if the user mouses over it.
    tooltip" . $action->id . ".get().mouseout(function() {
        tooltip" . $action->id . ".remove();
    });
    
    $('#add-comment-form-" . $action->id . "').on('beforeSubmit', function (e) {
        e.preventDefault();
        if($(this).find('.has-error').length == 0) {
            var commentText = $('#add-comment-form-" . $action->id . " #comments-content');
            var commentActionID = $('#add-comment-form-" . $action->id . " #comments-action_id');
            if(commentText[0].value.length != 0){
                $.ajax({
                    url: '" . Url::to(['/add-comment']) . "',
                    type: 'POST',
                    data:{
                        'Comments':{
                            'content': commentText[0].value,
                            'action_id': commentActionID[0].value
                        }
                    },
                    success: function(response) {
                        result = JSON.parse(response);
                        if (result.html.length != 0) {
                            $('#comments-" . $action->id . "').append(result.html).show('slow');
                            $('#modal-comments-" . $action->id . "').append(result.html).show('slow');
                            document.getElementById('add-comment-form-" . $action->id . "').reset();
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
?>
