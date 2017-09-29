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

$this->title = 'Pending Amount Reports';
$this->params['breadcrumbs'][] = $this->title;
//print_r($acc_model);
/* @var $this yii\web\View */
/* @var $model backend\models\DeliverySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pending-amount-search row">
<div class="col-sm-12">
    <h3 style="text-align:left">
        Pending Amount Reports 
    </h3>
	 <?php $form = ActiveForm::begin([ 'id' => 'pending-amount-search-horizontal',
										'type' => ActiveForm::TYPE_HORIZONTAL,
										'formConfig' => ['labelSpan' => 3, 
										'deviceSize' => ActiveForm::SIZE_LARGE],
										'action' => ['pending-amount-report'],
										'method' => 'get',
									]); ?>

    <?php // echo $form->field($model, 'product_id') ?>

<div class="row">

<div class="col-sm-5">
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
	/* 'rowOptions'=>function($model){
						if($model->unsettled == 1){
							return ['class' => 'danger'];
						}
				}, */
    'toolbar' => [
						['content'=>
							Html::a('') . Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['pending-amount-report'], ['data-pjax'=>false, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
						],
						'{export}',
						'{toggleData}'
					],
		 'panel' => [
						'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-cloud"></i> Pending Amount Reports  </h3>',
						'type'=>'primary',
						'before'=>Html::a(''),
						'after'=>Html::a(''),
						'showFooter'=>true
					],	
        'columns' => [
				
            ['class' => 'kartik\grid\SerialColumn'],
					
					[
						'attribute'=>'user_id',
						'vAlign'=>'left',		
							'filterType'=>GridView::FILTER_SELECT2,
							'filter'=>ArrayHelper::map($users, 'user_id', 'staff'), 
							'filterWidgetOptions'=>[
								'pluginOptions'=>['allowClear'=>true],
							],
							'filterInputOptions'=>['placeholder'=>'Any User'],
							'format'=>'raw',
							
					],
					[ 
						'attribute'=>'mobile',
						'vAlign'=>'right',
						'filterType'=>GridView::FILTER_SELECT2,
						'filter'=>ArrayHelper::map(Users::find()->orderBy('username')->asArray()->all(), 'id', 'username'), 
						'filterWidgetOptions'=>[
							'pluginOptions'=>['allowClear'=>true],
						],
						'filterInputOptions'=>['placeholder'=>'Any Mobile'],
						'format'=>'raw'
					], 
					[ 
						'attribute'=>'address',
						'filter'=>'',
						'format'=>'raw',
						'pageSummary'=>'Total',
						'pageSummaryOptions'=>['class'=>'text-right text-warning'],
							
						
					], 
					[ 
						'attribute'=>'previous_due_amount',
						'filter'=>'',
						'vAlign'=>'right',
						 'pageSummary'=>true,
						'pageSummaryFunc' => GridView::F_SUM, 
						'format'=>['decimal', 2],
						
					],
					[ 
						'attribute'=>'last_billed_amount',
						'filter'=>'',
						'vAlign'=>'right',
						 'pageSummary'=>true,
						'pageSummaryFunc' => GridView::F_SUM, 
						'format'=>['decimal', 2],
						
					],
					[ 	
						'attribute'=>'received_payment',
						'filter'=>'',
						'vAlign'=>'right',
						 'pageSummary'=>true,
						'pageSummaryFunc' => GridView::F_SUM, 
						'format'=>['decimal', 2],
					],
					[		
						'attribute'=>'pending_bill_amount',
						'pageSummary'=>true,
						'vAlign'=>'right',
						'format'=>['decimal', 2],
						'pageSummaryFunc' => GridView::F_SUM,
					],
					[			
						'attribute'=>'curr_mon_pending_amount',
						
						'vAlign'=>'right',
						'pageSummary'=>true,
						'format'=>['decimal', 2],
						'pageSummaryFunc' => GridView::F_SUM,
					],
					[
						'attribute'=>'delivery_boy_id',
						'vAlign'=>'left',
						'filter'=>'', 
							
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
