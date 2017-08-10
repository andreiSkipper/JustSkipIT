<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Lists */

$this->title = 'Create Lists';
$this->params['breadcrumbs'][] = ['label' => 'Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lists-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
