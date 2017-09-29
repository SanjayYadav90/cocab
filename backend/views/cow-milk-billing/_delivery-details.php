<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use kartik\grid\GridView;
use backend\models\Users;
use backend\models\Staff;
use backend\models\Products;
use backend\models\Address;
use backend\models\DefaultSetting;

/* @var $this yii\web\View */
/* @var $model backend\models\DeliverySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="delivery-index">
    <?= GridView::widget([
    'dataProvider'=>$dataProvider,
		 'panel' => [
						'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-cloud"></i> Customer Delivery Details  </h3>',
						'type'=>'primary',
						'before'=>Html::a(''),
						'after'=>Html::a(''),
						'showFooter'=>true
					],	
        'columns' => [
				
            ['class' => 'kartik\grid\SerialColumn'],
					
					[
						'attribute'=>'delivery_date',
						'vAlign'=>'middle',
						'filter'=>'',
						'group' => false,
						'format' => 'html',
						

						
					],

					[
						//'class' => 'kartik\grid\EditableColumn',			
						'attribute'=>'quantity',
						'vAlign'=>'middle',
						'pageSummary'=>true,
						'format'=>['decimal', 0],
						'filter'=>'',
					],
					[
						//'class' => 'kartik\grid\EditableColumn',			
						'attribute'=>'delivered',
						'pageSummary'=>true,
						'vAlign'=>'middle',
						'format'=>['decimal', 0],
						'filter'=>'',
					],
					
					[ 
						'attribute'=>'mrp',
						'value'=>function ($model, $key, $index, $widget) { 
								return isset($model->mrp) ? $model->mrp : null ;
							},
						'filter'=>'',
						'format'=>['decimal', 2],
						
					],
					[		
						'attribute'=>'area_discount',
						'pageSummary'=>true,
						'vAlign'=>'middle',
						'filter'=>'',
					], 
					[
						'class'=>'kartik\grid\FormulaColumn', 
						'header'=>'Total Amount<br>(Rs)', 
						'vAlign'=>'middle',
						'value'=>function ($model, $key, $index, $widget) { 
							$p = compact('model', 'key', 'index');
							return $widget->col(3, $p) * ($widget->col(4, $p) - $widget->col(5, $p));
						},
						'headerOptions'=>['class'=>'kartik-sheet-style'],
						'hAlign'=>'right', 
						'format'=>['decimal', 2],
						'mergeHeader'=>true,
						'pageSummary'=>true,
						'footer'=>true
					],
					[
						'attribute'=>'isdeliver',
						'vAlign'=>'middle',
						'value'=>function ($model, $key, $index, $widget) { 
								$status = DefaultSetting::find()->where(['type'=>'delivery','value'=> $model->isdeliver])->one();
								return isset($status) ? $status->name : '';
							},
							'filterType'=>GridView::FILTER_SELECT2,
							'filter'=>ArrayHelper::map($delivery_status, 'value', 'name'), 
							'filterWidgetOptions'=>[
								'pluginOptions'=>['allowClear'=>true],
							],
							'filterInputOptions'=>['placeholder'=>'Any Status'],
							'format'=>'raw', 
					],
					[
						'attribute'=>'delivery_time',
						'vAlign'=>'middle',
						'filter'=>'',
						'group' => false,
						'format' => 'html',
						

						
					],
				/* 	[ 	
						'attribute'=>'empty_bottle',
						'filter'=>'',
						 'pageSummary'=>true,
						'pageSummaryFunc' => GridView::F_SUM, 
					],
					[ 
						'attribute'=>'pending_bottle',
						
						'filter'=>'',
						 'pageSummaryFunc' => GridView::F_SUM,
						'pageSummary'=>true, 
					],
					[ 
						'attribute'=>'broken_bottle',
						'filter'=>'',
						 'pageSummary'=>true,
						'pageSummaryFunc' => GridView::F_SUM, 
					],	 */
			
			 
			 [
				'attribute'=>'delivery_boy_id',
				'vAlign'=>'middle',
				//'width'=>'160px',
				'value'=>function ($model, $key, $index, $widget) { 
						return isset($model->deliveryBoy->staff) ? $model->deliveryBoy->staff->first_name.' '.$model->deliveryBoy->staff->last_name : '' ;
							},
					'filterType'=>GridView::FILTER_SELECT2,
					'filter'=>ArrayHelper::map($d_boys, 'user_id', 'staff'), 
					'filterWidgetOptions'=>[
						'pluginOptions'=>['allowClear'=>true],
					],
					'filterInputOptions'=>['placeholder'=>'Any Boy'],
					'format'=>'raw',
					
			], 
				
								 
        ],
		//'showFooter' => true,
		'showPageSummary' => true,
		'responsive'=>true,
		'hover'=>true,	
]);
 ?>
</div>

