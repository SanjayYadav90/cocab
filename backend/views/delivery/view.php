<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Delivery */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Deliveries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="delivery-view">

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
            'product_id',
            'user_id',
            'delivery_date',
            'address_id',
            'quantity',
			'delivered',
            'area',
            'delivery_boy_id',
            'isdeliver',
			'area_discount' ,
			'unit' ,
            'mrp' ,
            'offer_price' ,
            'offer_unit',
            'offer_flag' ,
			'empty_bottle' ,
            'discounted_mrp' ,
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
        ],
    ]) ?>

</div>
