<?php
$notifications = \common\models\Notifications::find()->where(['user_id' => Yii::$app->user->identity->id])->orderBy(['created_at' => SORT_DESC])->all();
$notificationItems = array();
$notificationsCount = count($notifications);

if (!$notificationsCount) {
    ?>
    <li class="notification text-center no-padding"><a href="#" tabindex="-1">No notifications.</a></li>
    <?php
} else {
    foreach ($notifications as $notification) {
        $notificationUser = \common\models\User::find()->where(['id' => $notification->getModel()->user_id])->one();
        ?>
        <li class="notification <?= $notification->read ? '' : 'active' ?>">
            <a href="<?= '/post/' . $notification->getModel()->action_id . '#comment-' . $notification->getModel()->id ?>"
               tabindex="-1">
                <img src='/<?= $notificationUser->profile->avatar ?>' alt=''
                     data-url='<?= \common\models\Profiles::getProfileLinkByUserID($notificationUser->id) ?>'>
                <?= $notification->description ?>
                <span><?= date("d m Y H:i", $notification->created_at) ?></span>
            </a>
        </li>
    <?php }
} ?>