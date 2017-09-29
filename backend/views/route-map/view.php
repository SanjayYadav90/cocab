<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\RouteMap */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Route Maps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="route-map-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'route_id',
            'user_id',
            'sequence',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
