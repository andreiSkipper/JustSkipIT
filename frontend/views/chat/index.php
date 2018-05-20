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
<div class="col-xs-3 no-padding">
    <div class="text-center" id="users">
        <p>Users Online</p>
        <ul id="users-list">
        </ul>
    </div>
</div>

<script src='/js/socket.io.js'></script>

<script>
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
        $.each(users, function (index, data) {
            $('#users-list').append($('<li>').html('<img class="chat-avatar" alt="" src="' + data.profile.avatar + '">' + data.profile.lastname + ' ' + data.profile.firstname));
        });
    });

    $('#send-message-form').on('submit', function (event) {
        event.preventDefault();

        var message = $('#message').val();
        if (jQuery.trim(message).length > 0) {
            socket.emit('chat message', {
                user: model_user,
                profile: model_profile,
                message: message
            });
            $('#message').val('');
        }

        return false;
    });

    $('#message').on('input', function (e) {
        if ($(this).val().length) {
            socket.emit('typing', {
                user: model_user,
                profile: model_profile
            });
        } else {
            socket.emit('not-typing', {
                user: model_user,
                profile: model_profile
            });
        }
    });

    socket.on('typing', function (data) {
        if ($('#messages li#' + data.user.id).length === 0 && data.user.id !== model_user.id) {
            $('#messages').append($("<li id='" + data.user.id + "' class='typing'>").html('<img class="chat-avatar" alt="" src="' + data.profile.avatar + '">' + data.profile.lastname + ' ' + data.profile.firstname + ' is typing...'));
        }
    });

    socket.on('not-typing', function (data) {
        if ($('#messages li#' + data.user.id).length !== 0) {
            $('#messages li#' + data.user.id).remove();
        }
    });

    socket.on('chat message', function (data) {
        $('#messages li#' + data.user.id).remove();
        let html = '<img class="chat-avatar" alt="" src="' + data.profile.avatar + '">' + data.profile.lastname + ' ' + data.profile.firstname + ': ' + data.message;
        if ($('ul#messages > li.typing').length) {
            $('<li>').html(html).insertBefore($('ul#messages > li.typing'));
        } else {
            $('ul#messages').append($('<li>').html(html));
        }
        $('ul#messages').parent()[0].scrollTop = $('ul#messages').parent()[0].scrollHeight;
    });
</script>
