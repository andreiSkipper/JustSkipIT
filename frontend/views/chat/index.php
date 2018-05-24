<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="col-sm-9" id="chat-section">
    <div class="panel panel-default" id="chat">
        <div class="panel-heading text-center">
            <!--            Chat Box --->
            <!--            <input type="text" id="username" disabled/>-->
            <?= \common\models\Translations::translate('app', 'Global Chat') ?>
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
                <div class="col-xs-12 col-sm-9">
                    <?= Html::input('text', 'chat-input', '', [
                        'class' => 'form-control',
                        'id' => 'message',
                        'placeholder' => \common\models\Translations::translate('app', 'Send a message...'),
                        'autocomplete' => 'off'
                    ]) ?>
                </div>
                <div class="col-xs-12 col-sm-3">
                    <?= Html::submitButton(\common\models\Translations::translate('app', 'Send'), [
                        'class' => 'btn btn-danger col-xs-12',
                        'id' => 'send-message'
                    ]) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<div class="clearfix hidden-sm hidden-md hidden-lg"></div>
<div class="col-sm-3 no-padding">
    <div class="text-center" id="users">
        <p><?= \common\models\Translations::translate('app', 'Users Online') ?></p>
        <ul id="users-list">
        </ul>
    </div>
</div>

<script src='/js/socket.io.js'></script>

<script>
    var model_user = <?= json_encode(\common\models\User::find()->where(['id' => Yii::$app->user->identity->id])->one()->toArray()) ?>;
    var model_profile = <?= json_encode(\common\models\Profiles::find()->where(['user_id' => Yii::$app->user->identity->id])->one()->toArray()) ?>;
    // var socket = io('http://ec2-35-159-26-29.eu-central-1.compute.amazonaws.com:3002');
    var socket = io('http://localhost:3002');
    var user;

    var audio_new_message = new Audio('/sounds/notification_message.mp3');

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
    socket.on('update', function (request) {
        $('#users-list').empty();
        $.each(request.users, function (index, user) {
            if ($('#users-list li#' + user.user.id).length === 0) {
                $('#users-list').append($("<li id='" + user.user.id + "'>").html('<img class="chat-avatar" alt="" src="' + user.profile.avatar + '">' + user.profile.lastname + ' ' + user.profile.firstname));
            }
        });

        if ($('#users-list li#' + request.user.user.id).length === 0) {
            $('#messages').append($("<li>").html('<img class="chat-avatar" alt="" src="' + request.user.profile.avatar + '">' + request.user.profile.lastname + ' ' + request.user.profile.firstname + ' has left the chat...'));
            scrollChatBottom();
        } else {
            if (request.user.user.id !== model_user.id) {
                $('#messages').append($("<li>").html('<img class="chat-avatar" alt="" src="' + request.user.profile.avatar + '">' + request.user.profile.lastname + ' ' + request.user.profile.firstname + ' has joined the chat...'));
                scrollChatBottom();
            }
        }
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

    socket.on('typing', function (request) {
        if ($('#messages li#' + request.user.id).length === 0 && request.user.id !== model_user.id) {
            $('#messages').append($("<li id='" + request.user.id + "' class='typing'>").html('<img class="chat-avatar" alt="" src="' + request.profile.avatar + '">' + request.profile.lastname + ' ' + request.profile.firstname + ' is typing...'));
            scrollChatBottom();
        }
    });

    socket.on('not-typing', function (request) {
        if ($('#messages li#' + request.user.id).length !== 0) {
            $('#messages li#' + request.user.id).remove();
            scrollChatBottom();
        }
    });

    socket.on('chat message', function (request) {
        $('#messages li#' + request.user.id).remove();
        let html = '<img class="chat-avatar" alt="" src="' + request.profile.avatar + '">' + request.profile.lastname + ' ' + request.profile.firstname + ': ' + request.message;
        if ($('ul#messages > li.typing').length) {
            $('<li>').html(html).insertBefore($('ul#messages > li.typing')[0]);
        } else {
            $('ul#messages').append($('<li>').html(html));
        }
        if (request.user.id !== model_user.id) {
            audio_new_message.cloneNode().play();
        }
        scrollChatBottom();
    });

    function scrollChatBottom() {
        $('ul#messages').parent()[0].scrollTop = $('ul#messages').parent()[0].scrollHeight;
    }
</script>
