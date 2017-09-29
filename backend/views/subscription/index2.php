<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\Users;
use kartik\widgets\Select2;
use backend\models\Staff;
use backend\models\Products;
use backend\models\DefaultSetting;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SubscriptionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Subscriptions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
	<?= $this->render('_search2', ['model' => $searchModel,'users' => $users,'products' => $products]); ?>

</div>
<div class="subscription-index2">

     
	
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
		'pjax'=>true,
		'toolbar' => [
						['content'=>
							Html::a('<i class="glyphicon glyphicon-plus"></i> ',[''], ['type'=>'button',
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
					[
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
            
						
					],
            
			
            'quantity',
			[
				'class'=>'kartik\grid\BooleanColumn',
				'attribute'=>'status', 
				'vAlign'=>'middle',
				//'filter' => Html::activeDropDownList($searchModel, 'status', ArrayHelper::map(DefaultSetting::find()->where(['type'=>'price'])->all(), 'value', 'name'),['class'=>'form-control','prompt' => 'Select Status']),
					
			], 
			
			//'xrefPauseSubscriptions.quantity',
            /* [
				'attribute'=>'created_at',
				'vAlign'=>'middle',
				
				'value' =>function ($model, $key, $index) {
						
						return  $date_time = date('Y-m-d H:i:s', $model->created_at);	
						},
			],  */
           

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
