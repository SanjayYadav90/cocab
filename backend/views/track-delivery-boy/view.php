<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\TrackDeliveryBoy */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Track Delivery Boys', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="track-delivery-boy-view">

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
            'date_time',
            'position',
            'delivery_boy_id',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
