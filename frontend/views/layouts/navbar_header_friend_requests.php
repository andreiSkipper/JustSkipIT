<?php
$friendships = \common\models\Friendship::getFriendRequestsForCurrent();

if (empty($friendships['Requests'])) {
    ?>
    <li class="friend-request text-center no-padding"><a href="#" tabindex="-1">No friend requests.</a></li>
    <?php
} else {
    foreach ($friendships['Requests'] as $friendRequest) {
        ?>
        <li class="friend-request">
            <a href="#" tabindex="-1">
                <img src='/<?= $friendRequest->getUserFrom()->one()->getProfile()->one()->avatar ?>' alt=''
                     data-url='<?= \common\models\Profiles::getProfileLinkByUserID($friendRequest->user_from) ?>'>
                <?= $friendRequest->getUserFrom()->one()->getFullName() ?>
                <span class='fa fa-check-circle-o fa-2x' data-user_id='<?= $friendRequest->user_from ?>'
                      onclick='return acceptFriendAJAX(this);'></span>
                <span class='fa fa-times-circle-o fa-2x' data-user_id='<?= $friendRequest->user_from ?>'
                      onclick='return refuseFriendAJAX(this);'></span>
            </a>
        </li>
    <?php }
} ?>

<?php if (!empty($friendships['Requested'])) { ?>
    <li class="dropdown-header">Requested:</li>
<?php } ?>

<?php foreach ($friendships['Requested'] as $friendRequest) { ?>
    <li class="friend-request">
        <a href="#" tabindex="-1">
            <img src='/<?= $friendRequest->getUserTo()->one()->getProfile()->one()->avatar ?>' alt=''
                 data-url='<?= \common\models\Profiles::getProfileLinkByUserID($friendRequest->user_to) ?>'>
            <?= $friendRequest->getUserTo()->one()->getFullName() ?>
            <span class='fa fa-times-circle-o fa-2x' data-user_id='<?= $friendRequest->user_to ?>'
                  onclick='return removeFriendAJAX(this);'></span>
        </a>
    </li>
<?php } ?>