<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\helpers\Url;
use backend\models\DefaultSetting;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-index">

   <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax'=>true,
		'toolbar' => [
						['content'=>
							Html::a('<i class="glyphicon glyphicon-plus"></i> ',['create'], ['type'=>'button',
								'title'=>'Add New Products', 'class'=>'btn btn-success']) . Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax'=>false, 'class' => 'btn btn-default', 'title'=>'Reset Products'])
						],
						'{export}',
						'{toggleData}'
					],
		 'panel' => [
						'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-user"></i> Products </h3>',
						'type'=>'primary',
						//'before'=>,
						'after'=>Html::a(''),
						'showFooter'=>false
					],		
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            'name',
            'description',
            [
				'attribute' =>'price',
				'contentOptions' => ['class' => 'col-lg-1'],
				'format'=>['decimal',2]
			],
            [
                'attribute' => 'image',
                'format' => 'html',
                'label' => 'Image',
                'value' => function ($data) {
                    return $data->DisplayImage;
                },
            ],
			[
				'attribute' => 'cat_id', 
				'value' => 'category.name',  
					'filterType' => GridView::FILTER_SELECT2,
					'filter' => ArrayHelper::map($cat_list,'id', 'name'),
					 'filterWidgetOptions' => [
						'pluginOptions' => ['allowClear' => true],
					],
					'filterInputOptions' => ['placeholder' => 'Select Category'], 	
			],
			[
				'attribute'=>'status',
				'value' =>function ($model, $key, $index) {						
						return  DefaultSetting::getConfigByValue($model->status,"product");	
						},
				'filterType' => GridView::FILTER_SELECT2,
				'width'=>'auto',
				'filter' => ArrayHelper::map($product_status,'value', 'name'),
				 'filterWidgetOptions' => [
					'pluginOptions' => ['allowClear' => true],
				],
				'filterInputOptions' => ['placeholder' => 'Select Status'], 
			],
			[
				'attribute'=>'brand_id',	
				'value' =>	'brand.name',
				'width'=>'auto',
				'filterType' => GridView::FILTER_SELECT2,
				'filter' => ArrayHelper::map($brands,'id', 'name'),
				 'filterWidgetOptions' => [
					'pluginOptions' => ['allowClear' => true],
				],
				'filterInputOptions' => ['placeholder' => 'Select Brand Name'], 				
							
			],
			[
				'attribute'=>'delivery_slot_id',	
				'value' =>	'deliverySlot.delivery_slot',
				'width'=>'auto',
				'filterType' => GridView::FILTER_SELECT2,
				'filter' => ArrayHelper::map($slots,'id', 'delivery_slot'),
				 'filterWidgetOptions' => [
					'pluginOptions' => ['allowClear' => true],
				],
				'filterInputOptions' => ['placeholder' => 'Select Slot Name'], 				
							
			],
			 [
				'attribute'=>'product_filter',
				'width'=>'auto',
				'filterType' => GridView::FILTER_SELECT2,
				'filter' => ArrayHelper::map($prod_filter,'name', 'name'),
				 'filterWidgetOptions' => [
					'pluginOptions' => ['allowClear' => true],
				],
				'filterInputOptions' => ['placeholder' => 'Select Filter Name'], 
			],
			[
				'attribute'=>'created_at',
				'vAlign'=>'middle',
				'value' =>function ($model, $key, $index) {						
						return  $date_time = date('Y-m-d H:i:s', $model->created_at);	
						},
			],
			/*[
				'attribute'=>'updated_at',
				'vAlign'=>'middle',
				'value' =>function ($model, $key, $index) {						
						return  $date_time = date('Y-m-d H:i:s', $model->updated_at);	
						},
			] */
            // 'image_name',
            // 'created_at',
            // 'updated_at',
            // 'created_by',
            // 'updated_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
		'responsive'=>true,
			'hover'=>true,
			'exportConfig' => [
						GridView::CSV => ['label' => 'Save as CSV'],
						GridView::HTML => ['label' => 'Save as HTML'],
						GridView::PDF => ['label' => 'Save as PDF'],
						GridView::EXCEL=> ['label' => 'Save as EXCEL'],
						GridView::TEXT=> ['label' => 'Save as TEXT'],
					],
			'export' => [
						'fontAwesome' => true
						],
    ]); ?>
</div>
