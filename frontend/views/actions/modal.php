<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Profiles;
use common\models\Actions;
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

<?php
Modal::end();
?>