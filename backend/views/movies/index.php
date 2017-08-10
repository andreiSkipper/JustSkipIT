<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Movies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="movies-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="row">
        <?= Html::a('Create Movies', ['create'], ['class' => 'btn btn-success col-md-6 col-md-offset-3']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'duration',
            'year',
            'genre',
            'director',
            'release_date',
            'stars',
            'video',
            'image',
//            'plot',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
