<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use common\models\Translations;

?>
<div class="col-xs-12">
    <h3 class="text-center"><?= Translations::translate('app', 'CREATE YOUR ACCOUNT') ?></h3>
    <?php $form = ActiveForm::begin(['id' => 'signup-form', 'action' => ['/signup']]); ?>


    <?=
    $form->field($model, 'username', [
        'addon' => [
            'prepend' => [
                'content' => '<i class="fa fa-user-circle-o faa-pulse animated"></i>'
            ]
        ]
    ])->textInput(
        [
            'placeholder' => Translations::translate('app', 'USERNAME'),
            'name' => 'username'
        ]
    )->label(false);
    ?>

    <?=
    $form->field($model, 'email', [
        'addon' => [
            'prepend' => [
                'content' => '<i class="fa fa-envelope-o faa-pulse animated"></i>'
            ]
        ]
    ])->textInput(
        [
            'placeholder' => Translations::translate('app', 'EMAIL'),
            'name' => 'email'
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
            'placeholder' => Translations::translate('app', 'PASSWORD'),
            'name' => 'password'
        ]
    )->label(false);
    ?>

    <?=
    $form->field($model, 'firstname', [
        'addon' => [
            'prepend' => [
                'content' => '<i class="fa fa-user-o faa-pulse animated"></i>'
            ]
        ]
    ])->textInput(
        [
            'placeholder' => Translations::translate('app', 'FIRST NAME'),
            'name' => 'firstname'
        ]
    )->label(false);
    ?>

    <?=
    $form->field($model, 'lastname', [
        'addon' => [
            'prepend' => [
                'content' => '<i class="fa fa-user faa-pulse animated"></i>'
            ]
        ]
    ])->textInput(
        [
            'placeholder' => Translations::translate('app', 'LAST NAME'),
            'name' => 'lastname'
        ]
    )->label(false);
    ?>

    <div class="clearfix"></div>

    <div class="row">
        <div class="form-group col-xs-6">
            <?= Html::submitButton('<i class="fa fa-file-text-o faa-wrench"></i> SIGN-UP', ['class' => 'btn btn-orange col-xs-12 faa-parent animated-hover', 'name' => 'signup-button', 'data-method' => 'post']) ?>
        </div>

        <div class="form-group col-xs-6">
            <?= Html::button('<i class="fa fa-times faa-pulse"></i> CANCEL', ['class' => 'btn btn-primary col-xs-12 faa-parent animated-hover', 'name' => 'cancel-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
