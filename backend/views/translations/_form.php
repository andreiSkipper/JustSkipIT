<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \common\models\Translations */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="translations-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'message')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ro')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::resetButton($model->isNewRecord ? 'Reset' : 'Reset', ['class' => $model->isNewRecord ? 'btn btn-success col-md-3 col-md-offset-2' : 'btn btn-primary col-md-3 col-md-offset-2']) ?>
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success col-md-3 col-md-offset-2' : 'btn btn-primary col-md-3 col-md-offset-2']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
