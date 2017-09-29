<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\Users;
use kartik\widgets\Select2;
use backend\models\Staff;
use backend\models\Products;
use kartik\editable\Editable;
use backend\models\DefaultSetting;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SubscriptionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Subscriptions';
$this->params['breadcrumbs'][] = $this->title;
$status_sub = DefaultSetting::find()->where(['type'=>'subscription'])->andWhere(['<>','value',0])->All();
?>
<div class="subscription-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' =>  $searchModel,
		'pjax'=>true,
		'toolbar' => [
						['content'=>
							Html::a('<i class="glyphicon glyphicon-plus"></i> ',['create'], ['type'=>'button',
								'title'=>'Add New Subscription', 'class'=>'btn btn-success']) . Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax'=>false, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
						],
						'{export}',
						'{toggleData}'
					],
		 'panel' => [
						'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-user"></i> Product Subscription </h3>',
						'type'=>'primary',
						//'before'=>,
						'after'=>Html::a(''),
						'showFooter'=>false
					],	
			 'rowOptions'=>function($model){
						if($model->status == 2){
							return ['class' => 'info'];
						}
				},
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
					
                    [
						'attribute'=>'user_id',
						'vAlign'=>'middle',
						//'width'=>'180px',
						//'value' => 'users.staff.first_name',
						//'filter' => Html::activeDropDownList($searchModel, 'user_id', ArrayHelper::map($users, 'id', 'username'),['class'=>'form-control','prompt' => 'Select User Name']),
						 'value'=>function ($model, $key, $index, $widget) { 
								return Html::a( isset($model->users->staff) ? $model->users->staff->first_name.' '.$model->users->staff->last_name : '',  
									'#', 
									['title'=>'View user detail', 'onclick'=>'alert("This will open the user page.\n\nDisabled for now!")']);
							}, 
							'filterType'=>GridView::FILTER_SELECT2,
							'filter'=>ArrayHelper::map(Staff::find()->orderBy('first_name','last_name')->asArray()->all(), 'user_id', 'first_name'), 
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
						'value'=>'users.username',
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
						'attribute'=>'address',
						'vAlign'=>'middle',
						'value' => 'fullAddress',
						
					], 
					[
						'attribute'=>'product_id',
						'vAlign'=>'middle',
						//'value' => 'product.name',
						'width'=>'200px',
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
					
					/* [
						'class' => 'kartik\grid\EditableColumn',			
						'attribute'=>'area_discount',
						'editableOptions' => [
							'header' => 'Area Discount',
							//'asPopover'=>false,
							'inputType' => \kartik\editable\Editable::INPUT_TEXT,
							],
						'vAlign'=>'middle',
					], */
					
					[
						'attribute'=>'area_discount', 
						'vAlign'=>'middle',
						'value'=>'discount.area_discount',
						//'width'=>'160px',
						/* 'value'=>function ($model, $key, $index, $widget) { 
								return isset($model->deliveryBoy->staff) ? $model->deliveryBoy->staff->first_name.' '.$model->deliveryBoy->staff->last_name : '' ;
									}, */
							'filterType'=>GridView::FILTER_SELECT2,
							'filter'=>ArrayHelper::map($discount, 'id', 'area_name'), 
							'filterWidgetOptions'=>[
								'pluginOptions'=>['allowClear'=>true],
							],
							'filterInputOptions'=>['placeholder'=>'Any Discount'],
							'format'=>'raw',
							'class' => 'kartik\grid\EditableColumn',
							'editableOptions' => [
										'header' => 'Any Discount', 
										'inputType' => Editable::INPUT_DROPDOWN_LIST,
										'data'=>ArrayHelper::map($discount, 'id', 'area_name'), // any list of values
										'options' => ['class'=>'form-control', 'prompt'=>'Select Discount...'],
									],
					],
            //'id',
					[
						'attribute'=>'start_date',
						'vAlign'=>'middle',
						'width'=>'200px',
						'filterType'=> \kartik\grid\GridView::FILTER_DATE, 
						'filterWidgetOptions' => [
								'options' => ['placeholder' => 'Select Start date'],
								'pluginOptions' => [
									'format' => 'yyyy-mm-dd',
									'todayHighlight' => true,
									'autoclose' => true,
								]
						],
						'group' => false,
						'format' => 'html'

						
					],
					/* [
						'attribute'=>'end_date',
						'vAlign'=>'middle',
						'width'=>'200px',
						'filterType'=> \kartik\grid\GridView::FILTER_DATE, 
						'filterWidgetOptions' => [
								'options' => ['placeholder' => 'Select End date'],
								'pluginOptions' => [
									'format' => 'yyyy-mm-dd',
									'todayHighlight' => true,
									'autoclose' => true,
								]
						],
						'group' => false,
						'format' => 'html'
            
						
					], */
            
			
					[
						'class' => 'kartik\grid\EditableColumn',			
						'attribute'=>'quantity',
						'editableOptions' => [
							'header' => 'Subscription Quantity',
							//'asPopover'=>false,
							'inputType' => \kartik\editable\Editable::INPUT_TEXT,
							],
						'vAlign'=>'middle',
					],
			[
				'attribute'=>'status', 
				'vAlign'=>'middle',
				'value'=>function ($model, $key, $index, $widget) { 
						$status = DefaultSetting::find()->where(['type'=>'subscription','value'=> $model->status])->one();
						return isset($status) ? $status->name : '';
					},
				'filterType'=>GridView::FILTER_SELECT2,
							'filter'=>ArrayHelper::map($subscription_status, 'value', 'name'), 
							'filterWidgetOptions'=>[
								'pluginOptions'=>['allowClear'=>true],
							],
							'filterInputOptions'=>['placeholder'=>'Any Status'],
							'format'=>'raw',
			], 
			
			//'xrefPauseSubscriptions.quantity',
             [
				'attribute'=>'created_at',
				'vAlign'=>'middle',
				
				'value' =>function ($model, $key, $index) {
						
						return  $date_time = date('Y-m-d H:i:s', $model->created_at);	
						},
			], 
            [	
					'class' => 'kartik\grid\ActionColumn',
					'vAlign'=>'middle',
					'template' => '{view}    {update}    {unsubscribe}',
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
					
						
					'unsubscribe' =>function ($url, $model) {     
										return Html::a('<span class="glyphicon glyphicon-off"></span>', $url, [
												'title' => Yii::t('yii', 'Unsubscribe Subscription'),
												//'onclick' => 'js:OpenModalDialog("'.$url.'")',
												'data-pjax' => '0',
										]);                             

									}, 
				],
			'urlCreator' => function ($action, $model, $key, $index) {
						 if ($action === 'unsubscribe') {
							$url = Yii::$app->urlManager->createUrl(['/subscription/unsubscribe',"id" => $model->id,]); // your own url generation logic
							return $url;
						} 
						if ($action === 'view') {
							$url = Yii::$app->urlManager->createUrl(['/subscription/view',"id" => $model->id,]);
							return $url;
						}
						if ($action === 'update') {
							$url = Yii::$app->urlManager->createUrl(['/subscription/update',"id" => $model->id,]); // your own url generation logic
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
    ]); ?>
</div>
