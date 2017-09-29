<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\DefaultSetting;

/* @var $this yii\web\View */
/* @var $model backend\models\DeliverySlotName */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Delivery Slot Names', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="delivery-slot-name-view">
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
            'delivery_slot',
            'delivery_charge',
           [
				'attribute'=>'status',	
				'value' =>	DefaultSetting::getConfigByValue($model->status,'price'),	
							
			],
            [
				'attribute'=>'created_at',	
				'value' =>	date('Y-m-d H:i:s', $model->created_at),	
							
			],
            [
				'attribute'=>'updated_at',	
				'value' =>	date('Y-m-d H:i:s', $model->updated_at),	
							
			],
        ],
    ]) ?>

</div>
