<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Movies */
/* @var $form yii\widgets\ActiveForm */

use yii\jui\DatePicker;
use app\models\Genre;

$sql = Genre::find()->asArray()->all();

foreach ($sql as $genre) {
    $genres[$genre['name']] = $genre['name'];
}
?>

<div class="movies-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'plot')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'genre')->dropDownList($genres) ?>

    <?= $form->field($model, 'duration')->textInput() ?>

    <?= $form->field($model, 'year')->textInput() ?>

    <?= $form->field($model, 'director')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'release_date')->widget(DatePicker::className(), ['dateFormat' => 'yyyy-MM-dd',]) ?>

    <?= $form->field($model, 'stars')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'video')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image')->fileInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::resetButton($model->isNewRecord ? 'Reset' : 'Reset', ['class' => $model->isNewRecord ? 'btn btn-success col-md-3 col-md-offset-2' : 'btn btn-primary col-md-3 col-md-offset-2']) ?>
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success col-md-3 col-md-offset-2' : 'btn btn-primary col-md-3 col-md-offset-2']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
