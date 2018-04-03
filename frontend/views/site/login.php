<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

$this->title = 'Just Skip IT';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <div class="site-login-body col-md-4 col-md-offset-4">
        <div class="panel panel-orange">
            <div class="panel-heading text-center">
                <h3 class="text-center">LOGIN TO YOUR ACCOUNT</h3>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <span class="login-icon fa fa-user"></span>
                <?=
                $form->field($model, 'username')->textInput(
                    [
                        'placeholder' => 'USERNAME',
                    ]
                )->label(false);
                ?>

                <span class="login-icon fa fa-lock"></span>
                <?=
                $form->field($model, 'password')->passwordInput(
                    [
                        'placeholder' => 'PASSWORD',
                    ]
                )->label(false);
                ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div style="color:#999;margin:1em 0">
                    If you forgot your password you can <?= Html::a('reset it', ['site/request-password-reset']) ?>.
                </div>

                <div class="form-group">
                    <?= Html::submitButton('<i class="fa fa-sign-in"></i> LOGIN', ['class' => 'btn btn-orange col-xs-12', 'name' => 'login-button', 'data-method' => 'post']) ?>
                </div>

                <div class="form-group">
                    <a href="signup">
                        <?= Html::button('<i class="fa fa-file-text-o"></i> SIGN-UP', ['class' => 'btn btn-primary col-xs-12', 'name' => 'signup-button']) ?>
                    </a>
                </div>

                <?php ActiveForm::end(); ?>
                <?= \yii\authclient\widgets\AuthChoice::widget(['baseAuthUrl' => ['site/auth']]) ?>
            </div>
        </div>
    </div>
</div>
