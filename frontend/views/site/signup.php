<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Just Skip IT';
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-signup">
    <div class="site-login-body col-md-4 col-md-offset-4">
        <div class="panel panel-orange">
            <div class="panel-heading text-center">
                <h3 class="text-center">CREATE YOUR ACCOUNT</h3>
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

                <span class="login-icon fa fa-envelope"></span>
                <?=
                $form->field($model, 'email')->textInput(
                    [
                        'placeholder' => 'EMAIL',
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

                <span class="login-icon fa fa-users"></span>
                <?=
                $form->field($model, 'firstname')->textInput(
                    [
                        'placeholder' => 'FIRST NAME',
                    ]
                )->label(false);
                ?>

                <span class="login-icon fa fa-users"></span>
                <?=
                $form->field($model, 'lastname')->textInput(
                    [
                        'placeholder' => 'LAST NAME',
                    ]
                )->label(false);
                ?>

                <div class="form-group">
                    <?= Html::submitButton('<i class="fa fa-file-text-o"></i> SIGN-UP', ['class' => 'btn btn-primary col-xs-12', 'name' => 'signup-button', 'data-method' => 'post']) ?>
                </div>

                <div class="form-group">
                    <a href="login">
                        <?= Html::button('<i class="fa fa-sign-in"></i> LOGIN', ['class' => 'btn btn-orange col-xs-12', 'name' => 'login-button']) ?>
                    </a>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>