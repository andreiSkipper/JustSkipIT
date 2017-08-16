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

<?php
Modal::end();
?>