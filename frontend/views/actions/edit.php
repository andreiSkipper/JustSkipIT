<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MoviesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Edit Post';
?>

<div class="panel panel-default text-center">
    <div class="panel-heading">
        Profile
    </div>

    <div class="panel-body">
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <div class="col-xs-12 no-padding">
            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($action, 'description')->textInput(
                        [
                            'maxlength' => true,
                            'placeholder' => 'Insert Description...'
                        ]);
                    ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($profile, 'lastname')->textInput(
                        [
                            'maxlength' => true,
                            'placeholder' => 'Insert Lastname...'
                        ]);
                    ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($profile, 'nickname')->textInput(
                        [
                            'maxlength' => true,
                            'placeholder' => 'Insert Nickname...'
                        ]);
                    ?>
                </div>
            </div>
        </div>


        <div class="col-xs-12 no-padding">

            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($profile, 'phoneNumber')->textInput(
                        [
                            'maxlength' => true,
                            'placeholder' => 'Insert Phone Number...',
                        ]);
                    ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($profile, 'birthday')->widget(DatePicker::className(), ['dateFormat' => 'dd-MM-yyyy',]) ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($profile, 'work')->textInput(
                        [
                            'maxlength' => true,
                            'placeholder' => 'Insert work...'
                        ]);
                    ?>
                </div>
            </div>
        </div>

        <div class="col-xs-12 no-padding">
            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($profile, 'currentCity')->dropDownList($cities) ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($profile, 'birthCity')->dropDownList($cities) ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($profile, 'knownLanguages')->dropDownList($languages) ?>
                </div>
            </div>
        </div>

        <div class="col-xs-12 no-padding">
            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($profile, 'sex')->dropDownList($profile->sexEnum) ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($profile, 'interestedIn')->dropDownList($profile->sexEnum) ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <?= $form->field($profile, 'relationship')->dropDownList($profile->relationshipEnum) ?>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-md-offset-4">
            <div class="form-group">
                <?= $form->field($profile, 'shortUrl')->textInput(
                    [
                        'maxlength' => true,
                        'placeholder' => 'Insert short url...'
                    ]);
                ?>
            </div>
        </div>

        <div class="col-xs-12 no-padding">
            <div class="col-md-6">
                <div class="form-group">
                    <?= $form->field($profile, 'address')->textarea(
                        [
                            'maxlength' => true,
                            'placeholder' => 'Insert Address...'
                        ]);
                    ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <?= $form->field($profile, 'description')->textarea(
                        [
                            'maxlength' => true,
                            'placeholder' => 'Insert description...'
                        ]);
                    ?>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="col-xs-12 no-padding form-group">
            <div class="col-md-6">
                <?= $form->field($profile, 'avatar')->fileInput(['maxlength' => true]) ?>
                <?php
                if ($profile->avatar AND file_exists($profile->avatar)) {
                    ?>
                    <img src="<?= Url::base() . '/' . $profile->avatar; ?>" class="col-xs-10 col-xs-offset-1">
                    <?php
                }
                ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($profile, 'cover')->fileInput(['maxlength' => true]) ?><?php
                if ($profile->cover AND file_exists($profile->cover)) {
                    ?>
                    <img src="<?= Url::base() . '/' . $profile->cover; ?>" class="col-xs-10 col-xs-offset-1">
                    <?php
                }
                ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6">
                <?= Html::resetButton('Reset', ['class' => 'btn btn-primary edit-profile-reset col-xs-8 col-xs-offset-2']) ?>
            </div>

            <div class="col-md-6">
                <?= Html::submitButton('Update', ['class' => 'btn btn-danger edit-profile-save col-xs-8 col-xs-offset-2', 'data-method' => 'post']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
