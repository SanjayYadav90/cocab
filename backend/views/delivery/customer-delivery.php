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

$this->title = 'View Customer Delivery';
$this->params['breadcrumbs'][] = $this->title;

/* @var $this yii\web\View */
/* @var $model backend\models\DeliverySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="delivery-search row">
<div class="col-sm-12">
    <h3 style="text-align:left">
        View Customer Delivery Reports 
    </h3>
	 <?php $form = ActiveForm::begin([ 'id' => 'delivery-search-horizontal',
										'type' => ActiveForm::TYPE_HORIZONTAL,
										'formConfig' => ['labelSpan' => 3, 
										'deviceSize' => ActiveForm::SIZE_LARGE],
										'action' => ['customer-delivery'],
										'method' => 'get',
									]); ?>

    <?php // echo $form->field($model, 'product_id') ?>
	
<div class="row">
<div class="col-sm-5">
  <?=$form->field($searchModel, 'delivery_date')->widget(DatePicker::classname(), [
			'options' => ['placeholder' => 'Select delivery Month ...'],
			'value' => date('Y-m'),
			'pluginOptions' => [
				'autoclose'=>true,
				'startView'=>'year',
                  'minViewMode'=>'months',
				'format' => 'yyyy-mm'
			]
		]); ?>
</div>
<div class="col-sm-5">
    <?= $form->field($searchModel, 'user_id')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($users,'user_id', 'staffMobile'),
			'options' => ['placeholder' => 'Select a Customer ...'],
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
<div class="col-sm-2">
  
</div>
</div>
    
<div class="row">
<div class="col-sm-5">

 <?= $form->field($searchModel, 'mobile', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-user"></i>']]])->textInput(['disabled'=>true,'id'=>'user-mobile'])->label('User Name') ?>
		 
</div>
<div class="col-sm-5">
<?= $form->field($searchModel, 'address_id', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-user"></i>']]])->textarea(['disabled'=>true,'id'=>'user-address','rows' => '3'])->label('User Address') ?>

</div>
<div class="col-sm-2">
<div class="form-group">
	
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
    
	</div>
</div>
</div>
<?php ActiveForm::end(); ?>
</div>
</div>
<div class="row">
<div class="col-sm-4"></div>
<div class="col-sm-4">
<div id="offer1">	

	
						</div>
</div>
<div class="col-sm-4"></div>
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
    'toolbar' => [
						['content'=>
							Html::a('') . Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['daily-report'], ['data-pjax'=>false, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
						],
						'{export}',
						'{toggleData}'
					],
		 'panel' => [
						'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-cloud"></i> Customer Delivery Reports  </h3>',
						'type'=>'primary',
						'before'=>Html::a(''),
						'after'=>Html::a(''),
						'showFooter'=>true
					],	
        'columns' => [
				
            ['class' => 'kartik\grid\SerialColumn'],
					
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
							'format'=>'raw',
							 'group'=>true,  // enable grouping
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
							} 
					],*/
					/* [ 
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
							
						
					],  */
					[
						'attribute'=>'delivery_date',
						'vAlign'=>'middle',
						//'width'=>'160px',
						'filter'=>'',
						/* 'filterType'=> \kartik\grid\GridView::FILTER_DATE, 
						'filterWidgetOptions' => [
								'options' => ['placeholder' => 'Select Delivery date'],
								'pluginOptions' => [
									'format' => 'yyyy-mm-dd',
									'todayHighlight' => true,
									'autoclose' => true,
								]
						],  */
						'group' => false,
						'format' => 'html',
						

						
					],

            //'id',
					
			
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
						 /* 'pageSummary'=>true,
						'pageSummaryFunc' => GridView::F_SUM,  */
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
						//'width'=>'160px',
						'filter'=>'',
						/* 'filterType'=> \kartik\grid\GridView::FILTER_DATE, 
						'filterWidgetOptions' => [
								'options' => ['placeholder' => 'Select Delivery date'],
								'pluginOptions' => [
									'format' => 'yyyy-mm-dd',
									'todayHighlight' => true,
									'autoclose' => true,
								]
						],  */
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

<script type="text/javascript">
	 $(document).ready(function(){
		$('#deliverysearch-user_id').on('change', function() {
			var url = '<?=Url::toRoute('subscription/userdetails',true)?>';
			$.ajax({
			  method: "POST",
			  url: url,
			  data: { user_id: this.value }
			})
			  .done(function( mobile ) {
				//$('#user-mobile').prop("disabled", false);
				$('#user-mobile').val(mobile);
				//$('#user-mobile').prop("disabled", false);
			  });
			  
			var url = '<?=Url::toRoute('subscription/useraddressdetails',true)?>';
			$.ajax({
			  method: "POST",
			  url: url,
			  data: { user_id: this.value }
			})
			  .done(function( address ) {
				//$('#user-mobile').prop("disabled", false);
				$('#user-address').val(address);
				//$('#user-mobile').prop("disabled", false);
			  });
		});
		
		var user_id = document.getElementById("deliverysearch-user_id").value;
		var url = '<?=Url::toRoute('subscription/userdetails',true)?>';
			$.ajax({
			  method: "POST",
			  url: url,
			  data: { user_id: user_id }
			})
			  .done(function( mobile ) {
				//$('#user-mobile').prop("disabled", false);
				$('#user-mobile').val(mobile);
				//$('#user-mobile').prop("disabled", false);
			  });
			  
			var url = '<?=Url::toRoute('subscription/useraddressdetails',true)?>';
			$.ajax({
			  method: "POST",
			  url: url,
			  data: { user_id: user_id }
			})
			  .done(function( address ) {
				//$('#user-mobile').prop("disabled", false);
				$('#user-address').val(address);
				//$('#user-mobile').prop("disabled", false);
			  });
		
  }); 
  

</script>
