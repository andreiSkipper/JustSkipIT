<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Movies */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Movies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default text-center">
    <div class="movie panel-heading">
        <h3>
            <a href="<?= Url::to(['movies/view', 'id' => $model->id]) ?>">
                <?= $model->title . ' (' . $model->year . ')'; ?>
            </a>
        </h3>
    </div>
    <div class="panel-body">
        <div class="col-md-4">
            <?php if ($model->image) { ?>
                <img src="<?= 'http://backend.dev/' . $model->image; ?>" class="col-xs-12">
            <?php } ?>
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="col-xs-6 text-right">
                    Genre:
                </div>
                <div class="col-xs-6 text-left">
                    <?= $model->genre; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6 text-right">
                    Release date:
                </div>
                <div class="col-xs-6 text-left">
                    <?php
                    $release_date = date("d.m.Y", strtotime($model->release_date));
                    echo $release_date;
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6 text-right">
                    Duration:
                </div>
                <div class="col-xs-6 text-left">
                    <?= $model->duration; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6 text-right">
                    Director:
                </div>
                <div class="col-xs-6 text-left">
                    <?= $model->director; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6 text-right">
                    Stars:
                </div>
                <div class="col-xs-6 text-left">
                    <?= $model->stars; ?>
                </div>
            </div>

            <div class="clearfix feature"></div>

            <div class="panel panel-default">
                <div class="panel-heading">PLOT</div>
                <div class="panel-body">
                    <?= $model->plot ?>
                </div>
            </div>

        </div>
        <div class="col-md-4">
            <iframe class="col-xs-12 no-padding" src="<?= $model->video; ?>" frameborder="0"
                    allowfullscreen></iframe>
        </div>
    </div>
</div>
