<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\Users;
use kartik\widgets\Select2;
use backend\models\Staff;
use backend\models\Products;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\XrefPauseSubscriptionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Edit/Pause Subscription ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="xref-pause-subscription-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'pjax'=>true,
		'toolbar' => [
						['content'=>
							Html::a('<i class="glyphicon glyphicon-plus"></i> ',['create'], ['type'=>'button',
								'title'=>'New Edit/Pause ', 'class'=>'btn btn-success']) . Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax'=>false, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
						],
						'{export}',
						'{toggleData}'
					],
		 'panel' => [
						'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-user"></i> Edit/Pause Subscription </h3>',
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
				'width'=>'180px',
				//'value' => 'users.staff.first_name',
				//'filter' => Html::activeDropDownList($searchModel, 'user_id', ArrayHelper::map($users, 'id', 'username'),['class'=>'form-control','prompt' => 'Select User Name']),
				'value'=>function ($model, $key, $index, $widget) { 
						return Html::a($model->users->staff->first_name.' '.$model->users->staff->last_name,  
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
				'attribute'=>'subscription_id',
				'vAlign'=>'middle',
				'width'=>'180px',
				'value'=>function ($model, $key, $index, $widget) { 
						return Html::a($model->subscription->product->name,  
							'#', 
							['title'=>'View product detail', 'onclick'=>'alert("This will open the prduct page.\n\nDisabled for now!")']);
					},
					'filterType'=>GridView::FILTER_SELECT2,
					'filter'=>ArrayHelper::map(Products::find()->orderBy('name')->asArray()->all(), 'id', 'name'), 
					'filterWidgetOptions'=>[
						'pluginOptions'=>['allowClear'=>true],
					],
					'filterInputOptions'=>['placeholder'=>'Any Products'],
					'format'=>'raw'
			],
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
            
			
            'quantity',
			
			
			[
				'attribute'=>'type',
				'filter'=>array("edit"=>"Edit","pause"=>"Pause"),
			],
			[
				'class'=>'kartik\grid\BooleanColumn',
				'attribute'=>'status', 
				'vAlign'=>'middle'
			],
           
			[	
					'class' => 'kartik\grid\ActionColumn',
					'vAlign'=>'middle',
					'template' => '{view}    {update}   ',
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
					
				],
			'urlCreator' => function ($action, $model, $key, $index) {
						if ($action === 'view') {
							$url = Yii::$app->urlManager->createUrl(['/xref-pause-subscription/view',"id" => $model->id,]);
							return $url;
						}
						if ($action === 'update') {
							$url = Yii::$app->urlManager->createUrl(['/xref-pause-subscription/update',"id" => $model->id,]); // your own url generation logic
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
