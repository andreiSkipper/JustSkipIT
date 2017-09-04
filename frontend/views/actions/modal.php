<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Profiles;
use common\models\Actions;
use common\models\Comments;
use kartik\form\ActiveForm;
use yii\bootstrap\Modal;
use yii\web\View;

/* @var $this View */
/* @var $action Actions */

$profile = Profiles::findOne($action->user_id);
$avatar = $profile->avatar ? Url::base() . '/' . $profile->avatar : '';
?>

<?php
Modal::begin([
    'id' => 'action-modal-' . $action->id,
    'header' => '
            <a href="' . Profiles::getProfileLinkByUserID($action->user_id) . '" class="action-link">
                <img src="' . $avatar . '" class="action-avatar" alt="">
            </a>

            <a href="' . Profiles::getProfileLinkByUserID($action->user_id) . '" class="action-link">
                ' . $profile->firstname . ' ' . $profile->lastname . '
            </a>
            ' . $action->getActionTextByType($action->type) . '
            ' . date("d M Y H:i:s", $action->created_at),
    'size' => 'modal-lg',
]);
?>

    <div class="row">
        <?php if ($action->description) { ?>
            <div class="col-xs-12 action-description">
                <?= $action->description ?>
            </div>
        <?php } ?>
        <div class="col-xs-12">
            <img src="<?= Url::base() . '/' . $action->imagePath; ?>" class="action-photo"
                 alt="">
        </div>
    </div>

    <br>

    <div class="action-comments">
        <div id="modal-comments-<?= $action->id ?>">
            <?php
            /* @var $comments Comments[] */
            $comments = Comments::find()->where(['action_id' => $action->id, 'reply_id' => null])->all();
            foreach ($comments as $comment) {
                echo $this->render('/comments/comment-details', ['comment' => $comment, 'modal' => true]);
                ?>
            <?php } ?>
        </div>
        <?php
        if (!Yii::$app->user->isGuest) {
            $model = new Comments();
            $model->action_id = $action->id;
            $form = ActiveForm::begin([
                'enableClientValidation' => false,
                'id' => 'add-comment-form-modal-' . $action->id
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
            <?php
            ActiveForm::end();
        }
        ?>
    </div>

<?php
$this->registerJs("
    $('#add-comment-form-modal-" . $action->id . "').on('beforeSubmit', function (e) {
        e.preventDefault();
        if($(this).find('.has-error').length == 0) {
            var commentText = $('#add-comment-form-modal-" . $action->id . " #comments-content');
            var commentActionID = $('#add-comment-form-modal-" . $action->id . " #comments-action_id');
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
                            $('#modal-comments-" . $action->id . "').append(result.html).show('slow');
                            $('#comments-" . $action->id . "').append(result.html).show('slow');
                            document.getElementById('add-comment-form-modal-" . $action->id . "').reset();
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

<?php
Modal::end();
?>