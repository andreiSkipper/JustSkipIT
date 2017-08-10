<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MoviesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Movies';
//$this->params['breadcrumbs'][] = $this->title;
?>

<?php
if (Yii::$app->user->isGuest) {
    ?>
    <h1 class="text-center">Login to get access to this page!</h1>
    <?php
} else {

    foreach ($models as $movie) {

        ?>

        <div class="panel panel-default text-center">
            <div class="movie panel-heading">
                <h3>
                    <a href="<?= Url::to(['movies/view', 'id' => $movie->id]) ?>">
                        <?= $movie->title . ' (' . $movie->year . ')'; ?>
                    </a>
                </h3>
            </div>
            <div class="panel-body">
                <div class="col-md-4">
                    <?php if ($movie->image) { ?>
                        <img src="<?= '/' . $movie->image; ?>" class="col-xs-12">
                    <?php } ?>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-xs-6 text-right">
                            Genre:
                        </div>
                        <div class="col-xs-6 text-left">
                            <?= $movie->genre; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 text-right">
                            Release date:
                        </div>
                        <div class="col-xs-6 text-left">
                            <?php
                            $release_date = date("d.m.Y", strtotime($movie->release_date));
                            echo $release_date;
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 text-right">
                            Duration:
                        </div>
                        <div class="col-xs-6 text-left">
                            <?= $movie->duration; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 text-right">
                            Director:
                        </div>
                        <div class="col-xs-6 text-left">
                            <?= $movie->director; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 text-right">
                            Stars:
                        </div>
                        <div class="col-xs-6 text-left">
                            <?= $movie->stars; ?>
                        </div>
                    </div>

                    <div class="clearfix feature"></div>

                    <div class="panel panel-default">
                        <div class="panel-heading">PLOT</div>
                        <div class="panel-body">
                            <?= $movie->plot ?>
                        </div>
                    </div>

                </div>
                <div class="col-md-4">
                    <iframe class="col-xs-12 no-padding" src="<?= $movie->video; ?>" frameborder="0"
                            allowfullscreen></iframe>
                </div>
            </div>
        </div>

    <?php }

    echo \yii\widgets\LinkPager::widget([
        'pagination' => $pages,
    ]);

} ?>
