<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MoviesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="movies-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'title') ?>

<!--    --><?//= $form->field($model, 'title') ?>
<!---->
<!--    --><?//= $form->field($model, 'duration') ?>
<!---->
<!--    --><?//= $form->field($model, 'year') ?>
<!---->
<!--    --><?//= $form->field($model, 'genre') ?>

    <?php // echo $form->field($model, 'director') ?>

    <?php // echo $form->field($model, 'release_date') ?>

    <?php // echo $form->field($model, 'stars') ?>

    <?php // echo $form->field($model, 'video') ?>

    <?php // echo $form->field($model, 'image') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
