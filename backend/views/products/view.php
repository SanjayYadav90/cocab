<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\DefaultSetting;
use backend\models\Category;

/* @var $this yii\web\View */
/* @var $model backend\models\Products */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-view">

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
            'name',
            'description',
            //'price',
            
            [
				'attribute'=>'image',	
				'value' =>	Html::a(Html::img('http://'.$_SERVER['SERVER_NAME'].$model->image, ['alt'=>'product image', 'class'=>'thing', 'height'=>'auto', 'width'=>'100%']), ['site/zoom']),
				'format' => ['raw'],
							
			],
			[
				'attribute'=>'status',	
				'value' =>	DefaultSetting::getConfigByValue($model->status,'product'),	
							
			],
			[
				'attribute'=>'cat_id',	
				'value' =>	$model->category->name,	
							
			],
			[
				'attribute'=>'brand_id',	
				'value' =>	$model->brand->name,	
							
			],
			[
				'attribute'=>'delivery_slot_id',	
				'value' =>	$model->deliverySlot->delivery_slot,	
							
			],
			
			[
				'attribute'=>'product_filter',	
					
							
			],
            [
				'attribute'=>'created_at',	
				'value' =>	date('Y-m-d H:i:s', $model->created_at),	
							
			],
            [
				'attribute'=>'updated_at',	
				'value' =>	date('Y-m-d H:i:s', $model->updated_at),	
							
			],
			'popularity',
        ],
    ]) ?>

</div>
