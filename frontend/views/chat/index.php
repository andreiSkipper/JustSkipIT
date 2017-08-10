<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="col-xs-9" id="chat-section">
    <div class="panel panel-default" id="chat">
        <div class="panel-heading text-center">
            Chat Box -
            <input type="text" id="username" disabled/>
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
