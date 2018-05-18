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
                <div class="col-xs-9">
                    <?= Html::input('text', 'chat-input', '', [
                        'class' => 'form-control',
                        'id' => 'message',
                        'placeholder' => 'Send a message...'
                    ]) ?>
                </div>
                <div class="col-xs-3">
                    <?= Html::Button('Send', [
                        'class' => 'btn btn-danger col-xs-12',
                        'id' => 'send-message'
                    ]) ?>
                </div>
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

<script src='https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.1.1/socket.io.js'></script>

<script>
//    todo: fix user find and profile
    var model_user = <?= json_encode(\common\models\User::find(Yii::$app->user->identity->id)->one()->toArray()) ?>;
    var model_profile = <?= json_encode(\common\models\Profiles::find(['user_id' => Yii::$app->user->identity->id])->one()->toArray()) ?>;
    console.log(model_user);
    console.log(model_profile);
    var socket = io('http://localhost:3001');
    var user;

    if ($('#user_name').length) {
        user = jQuery.trim($('#user_name').html());
    } else {
        do {
            user = prompt('Username:');
            user = jQuery.trim(user);
        } while (user.length == 0);
    }

    socket.emit('join', user);
    socket.on('update', function (user) {
        $('#users-list').empty();
        $.each(user, function (index, value) {
            $('#users-list').append($('<li>').text(value));
        });
    });

    $('#send-message').click(function () {
        var $message = $('#message').val();
        var $username = user;
        if (jQuery.trim($username).length > 0) {
            if (jQuery.trim($message).length > 0) {
                socket.emit('chat message', {
                    username: model_profile.lastname + ' ' + model_profile.firstname,
                    message: $('#message').val(),
                    avatar: '/' + model_profile.avatar
                });
                $('#message').val('');
                return false;
            } else {
                alert('The message can not be empty');
            }
        }
        else {
            prompt('The username can not be empty, please choose a username: ');
        }
    });

    socket.on('chat message', function (data) {
        $('#messages').append($('<li>').html('<img class="chat-avatar" src="' + data.avatar + '">' + data.username + ': ' + data.message));
    });
</script>
