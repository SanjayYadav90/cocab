<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\DefaultSetting;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductFilter */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Product Filters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-filter-view">

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
            'name',
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
