<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Genre */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="genre-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php

//    $model['created_at'] = date('Y-m-d H:i:s');
//    $model['updated_at'] = date('Y-m-d H:i:s');

//    var_dump($model['created_at']);

    ?>

    <!--    --><? //= $form->field($model, 'created_at')->textInput()  ?>

    <!--    --><? //= $form->field($model, 'updated_at')->textInput()  ?>

    <div class="form-group">
        <?= Html::resetButton($model->isNewRecord ? 'Reset' : 'Reset', ['class' => $model->isNewRecord ? 'btn btn-success col-md-3 col-md-offset-2' : 'btn btn-primary col-md-3 col-md-offset-2']) ?>
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success col-md-3 col-md-offset-2' : 'btn btn-primary col-md-3 col-md-offset-2']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
