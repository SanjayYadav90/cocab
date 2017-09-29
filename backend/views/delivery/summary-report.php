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
use himiklab\jqgrid\JqGridWidget;

$this->title = 'Summary Daily Reports';
$this->params['breadcrumbs'][] = $this->title;

/* @var $this yii\web\View */
/* @var $model backend\models\DeliverySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="delivery-search row">
<div class="col-sm-12">
    <h3 style="text-align:left">
        Summary Daily Milk Delivery Reports 
    </h3>
	 <?php $form = ActiveForm::begin([ 'id' => 'delivery-search-horizontal',
										'type' => ActiveForm::TYPE_HORIZONTAL,
										'formConfig' => ['labelSpan' => 3, 
										'deviceSize' => ActiveForm::SIZE_LARGE],
										'action' => ['summary-report'],
										'method' => 'get',
									]); ?>

    <?php // echo $form->field($model, 'product_id') ?>

<div class="row">
<div class="col-sm-6">
  <?=$form->field($searchModel, 'delivery_date')->widget(DatePicker::classname(), [
			'options' => ['placeholder' => 'Select date ...'],
			'pluginOptions' => [
				'autoclose'=>true,
				'format' => 'yyyy-mm-dd'
			]
		]); ?>
</div>
<div class="col-sm-6">
    <div class="form-group">
	
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
    </div>
	</div>
	</div>
	

    <?php ActiveForm::end(); ?>

</div>

</div>
<div class="row">
<div class="col-sm-2"></div>
<div class="col-sm-8">
</div>
<div class="col-sm-2"></div>
</div>
<div class="delivery-index">

 <?= GridView::widget([
    'dataProvider'=>$dataProvider,
	'filterModel' => $searchModel,
	'pjax'=>true,
    'id' => 'grid',
	//'layout' => "{summary}\n{pager}\n{items}\n{pager}",
    'toolbar' => [
						['content'=>
							Html::a('') . Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['summary-report'], ['data-pjax'=>false, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
						],
						'{export}',
						'{toggleData}'
					],
		 'panel' => [
						'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-cloud"></i> summary Daily Milk Delivery Reports  </h3>',
						'type'=>'primary',
						'before'=>Html::a(''),
						'after'=>Html::a(''),
						'showFooter'=>true
					],	
        'columns' => [
				
            ['class' => 'kartik\grid\SerialColumn'],
					
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
					/* 'group'=>true,  // enable grouping
					'groupHeader'=>function ($model, $key, $index, $widget) { // Closure method
						return [
							'mergeColumns'=>[[1,2]], // columns to merge in summary
							'content'=>[             // content to show in each summary cell
								1=>'Total ',
								3=>GridView::F_SUM,
								4=>GridView::F_SUM,
								5=>GridView::F_SUM,
								6=>GridView::F_SUM,
								7=>GridView::F_SUM,
								8=>GridView::F_SUM,
								9=>GridView::F_SUM,
								
							],
							'contentFormats'=>[      // content reformatting for each summary cell
								3=>['format'=>'number', 'decimals'=>3],
								4=>['format'=>'number', 'decimals'=>2],
								5=>['format'=>'number', 'decimals'=>0],
								6=>['format'=>'number', 'decimals'=>0],
								7=>['format'=>'number', 'decimals'=>0],
								8=>['format'=>'number', 'decimals'=>0],
								9=>['format'=>'number', 'decimals'=>0],
							],
							'contentOptions'=>[      // content html attributes for each summary cell
								1=>['style'=>'font-variant:small-caps'],
								3=>['style'=>'text-align:right'],
								4=>['style'=>'text-align:right'],
								5=>['style'=>'text-align:right'],
								6=>['style'=>'text-align:right'],
								7=>['style'=>'text-align:right'],
								8=>['style'=>'text-align:right'],
								9=>['style'=>'text-align:right'],
							],
							// html attributes for group summary row
							'options'=>['class'=>'danger','style'=>'font-weight:bold;']
						];
					} */
					
				],
				[ 
						
						'label'=>'Route',
						'attribute'=>'route_name',
						'filter'=>'',
						'pageSummary'=>'Total',
						'pageSummaryOptions'=>['class'=>'text-right text-warning'], 
					],
					[ 
						
						'label'=>'Distance',
						'attribute'=>'distance',
						'filter'=>'',
						 'pageSummary'=>true,
						'pageSummaryFunc' => GridView::F_SUM, 
						'format'=>['decimal', 3],
						
					],
					[ 
						
						'label'=>'Collection Amount',
						'attribute'=>'amount',
						'filter'=>'',
						 'pageSummary'=>true,
						'pageSummaryFunc' => GridView::F_SUM, 
						'format'=>['decimal', 2],
						
					],
					[ 
						
						'label'=>'Order',
						'attribute'=>'quantity',
						'filter'=>'',
						 'pageSummary'=>true,
						'pageSummaryFunc' => GridView::F_SUM, 
						
					],
					[ 
						
						'label'=>'Delivered',
						'attribute'=>'delivered',
						'filter'=>'',
						 'pageSummary'=>true,
						'pageSummaryFunc' => GridView::F_SUM, 
					],
					/* [
						'attribute'=>'user_id',
						'vAlign'=>'middle',
						//'width'=>'160px',
						'value'=>function ($model, $key, $index, $widget) { 
								return Html::a(isset($model->user->staff) ? $model->user->staff->first_name.' '.$model->user->staff->last_name : '',  
									'#', 
									['title'=>'View user detail', 'onclick'=>'alert("This will open the user page.\n\nDisabled for now!")']);
							},
							'filterType'=>GridView::FILTER_SELECT2,
							'filter'=>ArrayHelper::map($users, 'user_id', 'staff'), 
							'filterWidgetOptions'=>[
								'pluginOptions'=>['allowClear'=>true],
							],
							'filterInputOptions'=>['placeholder'=>'Any User'],
							'format'=>'raw'
					],
					[ 
						'attribute'=>'mobile',
						'vAlign'=>'middle',
						//'label'=>'Mobile',
						'value'=>'user.username',
						//'width'=>'150px',
						'filterType'=>GridView::FILTER_SELECT2,
						'filter'=>ArrayHelper::map(Users::find()->orderBy('username')->asArray()->all(), 'id', 'username'), 
						'filterWidgetOptions'=>[
							'pluginOptions'=>['allowClear'=>true],
						],
						'filterInputOptions'=>['placeholder'=>'Any Mobile'],
						'format'=>'raw'
					], 
					[ 
						'attribute'=>'address_id',
						'vAlign'=>'middle',
						'value'=>function ($model, $key, $index, $widget) { 
								return isset($model->address) ? $model->address->address1.' '.$model->address->address2.' '.$model->address->city.' '.$model->address->pincode : '' ;
							},
							'filterType'=>GridView::FILTER_SELECT2,
							'filter'=>ArrayHelper::map(Address::find()->orderBy('address1','city')->asArray()->all(), 'id', 'address1'), 
							'filterWidgetOptions'=>[
								'pluginOptions'=>['allowClear'=>true],
							],
							'filterInputOptions'=>['placeholder'=>'Address 1'],
							'format'=>'raw'
						
					], 
					
					'area_discount',

            //'id',
					[
						'attribute'=>'delivery_date',
						'vAlign'=>'middle',
						//'width'=>'160px',
						'filterType'=> \kartik\grid\GridView::FILTER_DATE, 
						'filterWidgetOptions' => [
								'options' => ['placeholder' => 'Select Delivery date'],
								'pluginOptions' => [
									'format' => 'yyyy-mm-dd',
									'todayHighlight' => true,
									'autoclose' => true,
								]
						],
						'group' => false,
						'format' => 'html'

						
					],  */
			/* [
				'attribute'=>'isdeliver',
				'vAlign'=>'middle',
				
				//'value' => 'product.name',
				//'width'=>'100px',
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
			], */
			[ 	
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
			],	
								 
        ],
		//'showFooter' => true,
		'showPageSummary' => true,
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
]);
 ?>
</div>
