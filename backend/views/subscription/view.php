<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Subscription */

$this->title = "view subscription : ".$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Subscriptions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscription-view">

    <h1><?php //echo Html::encode($this->title) ?></h1>

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
            [
				'attribute'=>'user_id',	
				'value' =>	 $model->userName,	
							
			],
			[
				'attribute'=>'product_id',	
				'value' =>	 $model->productName,	
							
			],
			'start_date',
            'end_date',
            'quantity',
           [
				'attribute'=>'area_discount',	
				'value' =>	 isset($model->discount) ? $model->discount->area_discount : '',	
							
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
