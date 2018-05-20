<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="col-xs-9" id="chat-section">
    <div class="panel panel-default" id="chat">
        <div class="panel-heading text-center">
            <!--            Chat Box --->
            <!--            <input type="text" id="username" disabled/>-->
            Global Chat
        </div>
        <div class="panel-body" style="min-height: 450px">
            <div id="general-chat">
                <ul id="messages">
                </ul>
            </div>
        </div>
        <div class="panel-footer">
            <div class="row">
                <?php $form = ActiveForm::begin([
                    'id' => 'send-message-form',
                    'options' => [
                        'onsubmit' => 'return false;'
                    ]
                ]); ?>
                <div class="col-xs-9">
                    <?= Html::input('text', 'chat-input', '', [
                        'class' => 'form-control',
                        'id' => 'message',
                        'placeholder' => 'Send a message...',
                        'autocomplete' => 'off'
                    ]) ?>
                </div>
                <div class="col-xs-3">
                    <?= Html::submitButton('Send', [
                        'class' => 'btn btn-danger col-xs-12',
                        'id' => 'send-message'
                    ]) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<div class="col-xs-3">
    <div class="text-center" id="users">
        <p>Users Online</p>
        <ul id="users-list">
        </ul>
    </div>
</div>

<script src='/js/socket.io.js'></script>

<script>
    //    todo: fix user find and profile
    var model_user = <?= json_encode(\common\models\User::find()->where(['id' => Yii::$app->user->identity->id])->one()->toArray()) ?>;
    var model_profile = <?= json_encode(\common\models\Profiles::find()->where(['user_id' => Yii::$app->user->identity->id])->one()->toArray()) ?>;
    var socket = io('http://localhost:3002');
    var user;

    if ($('#user_name').length) {
        user = jQuery.trim($('#user_name').html());
    } else {
        do {
            user = prompt('Username:');
            user = jQuery.trim(user);
        } while (user.length == 0);
    }

    socket.emit('join', {
        user: model_user,
        profile: model_profile
    });
    socket.on('update', function (users) {
        $('#users-list').empty();
        $.each(users, function (index, value) {
            $('#users-list').append($('<li>').text(value.profile.lastname + ' ' + value.profile.firstname));
        });
    });

    $('#send-message-form').on('submit', function (event) {
        event.preventDefault();

        var message = $('#message').val();
        if (jQuery.trim(message).length > 0) {
            socket.emit('chat message', {
                user: model_user,
                profile: model_profile,
                message: message,
                avatar: '/' + model_profile.avatar
            });
            $('#message').val('');
        }

        return false;
    });

    socket.on('chat message', function (data) {
        $('#messages').append($('<li>').html('<img class="chat-avatar" alt="" src="' + data.avatar + '">' + data.profile.lastname + ' ' + data.profile.firstname + ': ' + data.message));
        $('ul#messages').parent()[0].scrollTop = $('ul#messages').parent()[0].scrollHeight;
    });
</script>
