<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

?>
<div class="col-xs-12">
    <h3 class="text-center">LOGIN TO YOUR ACCOUNT</h3>
    <?php $form = ActiveForm::begin(['id' => 'login-form', 'action' => ['/login']]); ?>

    <?=
    $form->field($model, 'username', [
        'addon' => [
            'prepend' => [
                'content' => '<i class="fa fa-user-circle-o faa-pulse animated"></i>'
            ]
        ]
    ])->textInput(
        [
            'placeholder' => 'USERNAME',
            'name' => 'username'
        ]
    )->label(false);
    ?>

    <?=
    $form->field($model, 'password', [
        'addon' => [
            'prepend' => [
                'content' => '<i class="fa fa-key faa-pulse animated"></i>'
            ]
        ]
    ])->passwordInput(
        [
            'placeholder' => 'PASSWORD',
            'name' => 'password'
        ]
    )->label(false);
    ?>

    <?= $form->field($model, 'rememberMe')->checkbox(['name' => 'rememberMe']) ?>

    <div class="row">
        <div class="form-group col-xs-6">
            <?= Html::submitButton('<i class="fa fa-sign-in faa-horizontal"></i> LOGIN', ['class' => 'btn btn-orange col-xs-12 faa-parent animated-hover', 'name' => 'signup-button', 'data-method' => 'post']) ?>
        </div>

        <div class="form-group col-xs-6">
            <?= Html::button('<i class="fa fa-times faa-pulse"></i> CANCEL', ['class' => 'btn btn-primary col-xs-12 faa-parent animated-hover', 'name' => 'cancel-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
