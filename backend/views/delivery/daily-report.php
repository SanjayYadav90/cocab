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
use backend\models\Delivery;

$this->title = 'Daily Milk Reports';
$this->params['breadcrumbs'][] = $this->title;

/* @var $this yii\web\View */
/* @var $model backend\models\DeliverySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="delivery-search row">
<div class="col-sm-12">
    <h3 style="text-align:left">
        Daily Milk Delivery Reports 
    </h3>
	 <?php $form = ActiveForm::begin([ 'id' => 'delivery-search-horizontal',
										'type' => ActiveForm::TYPE_HORIZONTAL,
										'formConfig' => ['labelSpan' => 3, 
										'deviceSize' => ActiveForm::SIZE_LARGE],
										'action' => ['daily-report'],
										'method' => 'get',
									]); ?>

    <?php // echo $form->field($model, 'product_id') ?>

<div class="row">
<div class="col-sm-4">
  <?=$form->field($searchModel, 'delivery_date')->widget(DatePicker::classname(), [
			'options' => ['placeholder' => 'Select date ...'],
			'pluginOptions' => [
				'autoclose'=>true,
				'format' => 'yyyy-mm-dd'
			]
		]); ?>
</div>
<div class="col-sm-4">
    <?= $form->field($searchModel, 'delivery_boy_id')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($d_boys,'user_id', 'first_name'),
			'options' => ['placeholder' => 'Select a D Boy ...'],
			'pluginOptions' => [
				'allowClear' => true,
				
			],
			'size' => Select2::MEDIUM,
			'addon' => [
				'prepend' => [
					'content' => '<i class="glyphicon glyphicon-user"></i>'
				],
			],
		]);  ?>

 </div>   
<div class="col-sm-4">
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
	 'rowOptions'=>function($model){
						if($model->unsettled == 1 ){
							return ['style' => 'background-color:#85C1E9'];  // blue
						}
						if($model->pause == 1 ){
							return ['style' => 'background-color:#feda7e'];  //color yellow
						}
						if($model->isdeliver != 1 && $model->delivered == 0){
							return ['style' => 'background-color:#F1948A'];  //red
						}
				},
    'toolbar' => [
						['content'=>
							Html::a('') . Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['daily-report'], ['data-pjax'=>false, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
						],
						'{export}',
						'{toggleData}'
					],
		 'panel' => [
						'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-cloud"></i> Daily Milk Delivery Reports  </h3>',
						'type'=>'primary',
						'before'=>Html::a(''),
						'after'=>Html::a(''),
						'showFooter'=>true
					],	
        'columns' => [
				
            ['class' => 'kartik\grid\SerialColumn'],
					
                [
				'class'=>'kartik\grid\ExpandRowColumn',
				'width'=>'50px',
				'value'=>function ($model, $key, $index, $widget) {
					return GridView::ROW_COLLAPSED;
				},
				  'detail'=>function ($model, $key, $index, $widget) {
					
					   $new_date = date('Y-m-d',strtotime('+1 day',strtotime($model->delivery_date)));
					  //$new_date = date("Y-m-d",strtotime('+1 day',$model->delivery_date));
					 $del_model = Delivery::find()->where(['user_id'=>$model->user_id , 'delivery_date' =>$new_date  ])->One();
					return Yii::$app->controller->renderPartial('_delivery-details', ['model'=>$del_model]);
				},  
				
				'headerOptions'=>['class'=>'kartik-sheet-style'] ,
				'expandOneOnly'=>true
			],
					
					[
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
							'format'=>'raw',
							/* 'group'=>true,  // enable grouping
							'groupHeader'=>function ($model, $key, $index, $widget) { // Closure method
								return [
									'mergeColumns'=>[[1,3]], // columns to merge in summary
									'content'=>[             // content to show in each summary cell
										1=>'Total ',
										4=>GridView::F_SUM,
										5=>GridView::F_SUM,
										
									],
									'contentFormats'=>[      // content reformatting for each summary cell
										4=>['format'=>'number', 'decimals'=>0],
										5=>['format'=>'number', 'decimals'=>0],
									],
									'contentOptions'=>[      // content html attributes for each summary cell
										1=>['style'=>'font-variant:small-caps'],
										4=>['style'=>'text-align:right'],
										5=>['style'=>'text-align:right'],
									],
									// html attributes for group summary row
									'options'=>['class'=>'danger','style'=>'font-weight:bold;']
								];
							} */
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
							'filterInputOptions'=>['placeholder'=>'Address...'],
							'format'=>'raw',
							'pageSummary'=>'Total',
							'pageSummaryOptions'=>['class'=>'text-right text-warning'],
							
						
					], 
					/* [
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
						'format' => 'html',
						

						
					], 
					[		
						'attribute'=>'area_discount',
						'pageSummary'=>true,
						'vAlign'=>'middle',
					], */

            //'id',
					
			
					[
						//'class' => 'kartik\grid\EditableColumn',			
						'attribute'=>'quantity',
						
						'vAlign'=>'middle',
						'pageSummary'=>true,
						'format'=>['decimal', 0],
					],
					[
						//'class' => 'kartik\grid\EditableColumn',			
						'attribute'=>'delivered',
						'pageSummary'=>true,
						'vAlign'=>'middle',
						'format'=>['decimal', 0],
					],
					
					[ 
						'label'=>'Collection Amount',
						'attribute'=>'amount',
						'value'=>function ($model, $key, $index, $widget) { 
								return isset($model->amount) ? $model->amount : 0 ;
							},
						'filter'=>'',
						 'pageSummary'=>true,
						'pageSummaryFunc' => GridView::F_SUM, 
						'format'=>['decimal', 2],
						
					],
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
			
			[
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
			],
			
			[
				'class'=>'kartik\grid\BooleanColumn',
				'attribute'=>'unsettled', 
				'vAlign'=>'middle'
			], 
			/* [
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
					
			], */
				
								 
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
