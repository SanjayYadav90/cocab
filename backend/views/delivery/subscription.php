<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\Users;
use kartik\widgets\Select2;
use backend\models\Staff;
use backend\models\Products;
use backend\models\Address;
use backend\models\DefaultSetting;
use kartik\editable\Editable;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\DeliverySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Deliveries';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php 
		$title='Deliveries';
		include( Yii::getAlias('@modaldialogs'));
	?>
<div class="delivery-index">
    <?= GridView::widget([
    'dataProvider'=>$dataProvider,
	'filterModel' => $searchModel,
	'pjax'=>true,
    'id' => 'grid',
    'toolbar' => [
						['content'=>
							Html::a('<i class="glyphicon glyphicon-plus"></i> ',[''], ['type'=>'button',
								'title'=>'Add New delivery', 'class'=>'btn btn-success']) . Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax'=>false, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
						],
						'{export}',
						'{toggleData}'
					],
		 'panel' => [
						'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-cloud"></i> Order Deliveries </h3>',
						'type'=>'primary',
						'before'=>Html::a('<i class="glyphicon glyphicon-cloud-download"></i>Load Orders ',['load'], ['type'=>'button',
								'title'=>'Add New Orders for delivery', 'class'=>'btn btn-primary']).' '.Html::button('<i class="glyphicon glyphicon-floppy-saved"></i>Copy order as delivery ', ['type'=>'button',
								'title'=>'selected order quantity fill as delivery quantity', 'class'=>'btn btn-primary','onclick'=>'js:OpenDialogForCheckbox("'.Url::to(['copydelivery']).'")']).' '.Html::button('<i class="glyphicon glyphicon-cloud"></i> Status as Delivered', ['type'=>'button', 
										'title'=>'Marks all order status as delivered', 'class'=>'btn btn-primary', 'onclick'=>'js:OpenDialogForCheckbox("'.Url::to(['markdelivered']).'")']).' '.Html::button('<i class="glyphicon glyphicon-user"></i> Change Delivery Boy', ['type'=>'button', 
										'title'=>'Changed Delivery Boy', 'class'=>'btn btn-primary', 'onclick'=>'js:OpenDialogForCheckbox("'.Url::to(['changedeliveryboy']).'")']),
						'after'=>Html::a('<i class="glyphicon glyphicon-cloud-download"></i>Load Orders ',['load'], ['type'=>'button',
								'title'=>'Add New Orders for delivery', 'class'=>'btn btn-primary']).' '.Html::button('<i class="glyphicon glyphicon-floppy-saved"></i>Copy order as delivery ', ['type'=>'button',
								'title'=>'selected order quantity fill as delivery quantity', 'class'=>'btn btn-primary','onclick'=>'js:OpenDialogForCheckbox("'.Url::to(['copydelivery']).'")']).' '.Html::button('<i class="glyphicon glyphicon-cloud"></i> Status as Delivered', ['type'=>'button', 
										'title'=>'Marks all order status as delivered', 'class'=>'btn btn-primary', 'onclick'=>'js:OpenDialogForCheckbox("'.Url::to(['markdelivered']).'")']).' '.Html::button('<i class="glyphicon glyphicon-user"></i> Change Delivery Boy', ['type'=>'button', 
										'title'=>'Changed Delivery Boy', 'class'=>'btn btn-primary', 'onclick'=>'js:OpenDialogForCheckbox("'.Url::to(['changedeliveryboy']).'")']),
						'showFooter'=>false
					],	
        'columns' => [
				['class' => 'kartik\grid\CheckboxColumn'],
				
            ['class' => 'kartik\grid\SerialColumn'],
					
                    [
						'attribute'=>'user_id',
						'vAlign'=>'middle',
						//'width'=>'160px',
						'value'=>function ($model, $key, $index, $widget) { 
								return Html::a($model->user->staff->first_name.' '.$model->user->staff->last_name,  
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
								return $model->address->address1.' '.$model->address->address2.' '.$model->address->city.' '.$model->address->pincode ;
							},
							'filterType'=>GridView::FILTER_SELECT2,
							'filter'=>ArrayHelper::map(Address::find()->orderBy('address1','city')->asArray()->all(), 'id', 'address1'), 
							'filterWidgetOptions'=>[
								'pluginOptions'=>['allowClear'=>true],
							],
							'filterInputOptions'=>['placeholder'=>'Address 1'],
							'format'=>'raw'
						
					], 
					[
						'attribute'=>'product_id',
						'vAlign'=>'middle',
						//'value' => 'product.name',
						//'width'=>'160px',
						'value'=>function ($model, $key, $index, $widget) { 
								return Html::a($model->product->name,  
									'#', 
									['title'=>'View Product detail', 'onclick'=>'alert("This will open the Product page.\n\nDisabled for now!")']);
							},
							'filterType'=>GridView::FILTER_SELECT2,
							'filter'=>ArrayHelper::map(Products::find()->orderBy('name')->asArray()->all(), 'id', 'name'), 
							'filterWidgetOptions'=>[
								'pluginOptions'=>['allowClear'=>true],
							],
							'filterInputOptions'=>['placeholder'=>'Any Product'],
							'format'=>'raw'
					],

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
						'group' => true,
						'format' => 'html'

						
					],
			
					[
						'class' => 'kartik\grid\EditableColumn',			
						'attribute'=>'quantity',
						'editableOptions' => [
							'header' => 'Order Quantity',
							//'asPopover'=>false,
							'inputType' => \kartik\editable\Editable::INPUT_TEXT,
							],
						'vAlign'=>'middle',
					],
					[
						'class' => 'kartik\grid\EditableColumn',			
						'attribute'=>'delivered',
						'editableOptions' => [
							'header' => 'Final Deliveries', 
							'inputType' => \kartik\editable\Editable::INPUT_TEXT,
							],
						'vAlign'=>'middle',
					],
			
			[
				'attribute'=>'isdeliver',
				'vAlign'=>'middle',
				//'value' => 'product.name',
				//'width'=>'100px',
				'value'=>function ($model, $key, $index, $widget) { 
						$status = DefaultSetting::find()->where(['type'=>'delivery','value'=> $model->isdeliver])->one();
						return $status->name;
					},
					'filterType'=>GridView::FILTER_SELECT2,
					'filter'=>ArrayHelper::map($delivery_status, 'value', 'name'), 
					'filterWidgetOptions'=>[
						'pluginOptions'=>['allowClear'=>true],
					],
					'filterInputOptions'=>['placeholder'=>'Any Status'],
					'format'=>'raw',
				'class' => 'kartik\grid\EditableColumn',
				'editableOptions' => [
								'header' => 'Delivery Status', 
							    //'format' => Editable::FORMAT_BUTTON,
								'inputType' => Editable::INPUT_DROPDOWN_LIST,
								'data'=>ArrayHelper::map($delivery_status, 'value', 'name'), // any list of values
								'options' => ['class'=>'form-control', 'prompt'=>'Select Status...'],
								//'editableValueOptions'=>['class'=>'text-danger']
							],
				
			],
			[
				'attribute'=>'delivery_boy_id',
				'vAlign'=>'middle',
				//'width'=>'160px',
				'value'=>function ($model, $key, $index, $widget) { 
						return $model->deliveryBoy->staff->first_name.' '.$model->deliveryBoy->staff->last_name ;
							},
					'filterType'=>GridView::FILTER_SELECT2,
					'filter'=>ArrayHelper::map($d_boys, 'user_id', 'staff'), 
					'filterWidgetOptions'=>[
						'pluginOptions'=>['allowClear'=>true],
					],
					'filterInputOptions'=>['placeholder'=>'Any Boy'],
					'format'=>'raw',
					'class' => 'kartik\grid\EditableColumn',
					'editableOptions' => [
								'header' => 'Delivery Boy', 
								'inputType' => Editable::INPUT_DROPDOWN_LIST,
								'data'=>ArrayHelper::map($d_boys, 'user_id', 'staff'), // any list of values
								'options' => ['class'=>'form-control', 'prompt'=>'Select D Boy...'],
							],
			],			
			
           /*  [
				'attribute'=>'created_at',
				'vAlign'=>'middle',
				
				'value' =>function ($model, $key, $index) {
						
						return  $date_time = date('Y-m-d H:i:s', $model->created_at);	
						},
			], */
			/* [
				'attribute'=>'updated_at',
				'vAlign'=>'middle',
				
				'value' =>function ($model, $key, $index) {
						
						return  $date_time = date('Y-m-d H:i:s', $model->updated_at);	
						},
			], */			
           [	
							'class' => 'kartik\grid\ActionColumn',
							'vAlign'=>'middle',
							'template' => '{view}    {update} ',
							'buttons' =>[
							 
							  'view' =>function ($url, $model) {     
												return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
														'title' => Yii::t('yii', 'View'),
														'data-pjax' => '0',
												]);                             

											},
							 'update' =>function ($url, $model) {     
												return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
														'title' => Yii::t('yii', 'Update'),
														//'onclick' => 'js:OpenModalDialog("'.$url.'")',
														'data-pjax' => '0',
												]);                             

											},
							
								
							   /* 'routecomplete' =>function ($url, $model) {     
												return Html::a('<span class="glyphicon glyphicon-off"></span>', $url, [
														'title' => Yii::t('yii', 'Route Completed'),
														//'onclick' => 'js:OpenModalDialog("'.$url.'")',
														'data-pjax' => '0',
												]);                             

											}, */
						],
					'urlCreator' => function ($action, $model, $key, $index) {
								/* if ($action === 'addstop') {
									$url = Yii::$app->urlManager->createUrl(['/route/addstop',"id" => $model->id,]); // your own url generation logic
									return $url;
								} */
								if ($action === 'view') {
									$url = Yii::$app->urlManager->createUrl(['/delivery/view',"id" => $model->id,]);
									return $url;
								}
								if ($action === 'update') {
									$url = Yii::$app->urlManager->createUrl(['/delivery/update',"id" => $model->id,]); // your own url generation logic
									return $url;
								}
								
										
							}
			
				],
		
            
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
]);
 ?>
</div>
