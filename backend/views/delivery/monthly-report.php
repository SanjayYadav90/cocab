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

$this->title = 'Monthly Reports';
$this->params['breadcrumbs'][] = $this->title;

/* @var $this yii\web\View */
/* @var $model backend\models\DeliverySearch */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="delivery-search row">
<div class="col-sm-12">
    <h3 style="text-align:left">
        Monthly Milk Delivery Reports 
    </h3>
	 <?php $form = ActiveForm::begin([ 'id' => 'delivery-search-horizontal',
										'type' => ActiveForm::TYPE_HORIZONTAL,
										'formConfig' => ['labelSpan' => 3, 
										'deviceSize' => ActiveForm::SIZE_LARGE],
										'action' => ['monthly-report'],
										'method' => 'get',
									]); ?>
<div class="row">
<div class="col-sm-6">
  <?=$form->field($searchModel, 'delivery_date')->widget(DatePicker::classname(), [
			'options' => ['placeholder' => 'Select year and month ...'],
			//'attribute2'=>'end_date',
			//'type' => DatePicker::TYPE_RANGE,
			'pluginOptions' => [
				'autoclose'=>true,
				 'startView'=>'year',
                  'minViewMode'=>'months',
				'format' => 'yyyy-mm'
			]
		]); ?>
</div>
<div class="col-sm-6">

    <?php // echo $form->field($model, 'quantity') ?>

    <?php // echo $form->field($model, 'area') ?>

    <?php // echo $form->field($model, 'delivery_boy_id') ?>

    <?php // echo $form->field($model, 'isdeliver') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

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
    'toolbar' => [
						['content'=>
							Html::a('') . Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['monthly-report'], ['data-pjax'=>false, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
						],
						'{export}',
						'{toggleData}'
					],
		 'panel' => [
						'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-cloud"></i> Monthly Milk Delivery Reports  </h3>',
						'type'=>'primary',
						'before'=>Html::a(''),
						'after'=>Html::a(''),
						'showFooter'=>true
					],	
        'columns' => [
				
            ['class' => 'kartik\grid\SerialColumn'],
					
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
							'format'=>'raw',
							'pageSummary'=>'Total',
						'pageSummaryOptions'=>['class'=>'text-right text-warning'],
							
						
					], 
					/* [
						'attribute'=>'delivery_date',
						'vAlign'=>'middle',
						//'width'=>'160px',
						'filter'=>'',
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
						'pageSummary'=>true,
						'vAlign'=>'middle',
					],
					[
						//'class' => 'kartik\grid\EditableColumn',			
						'attribute'=>'delivered',
						'pageSummary'=>true,
						'vAlign'=>'middle',
					],
			
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
