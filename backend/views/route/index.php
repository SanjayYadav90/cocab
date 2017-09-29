<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\helpers\Url;
use backend\models\DefaultSetting;
use kartik\editable\Editable;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RouteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Routes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="route-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax'=>true,
		'toolbar' => [
						['content'=>
							Html::a('<i class="glyphicon glyphicon-plus"></i> ',['create'], ['type'=>'button',
								'title'=>'Add New Route', 'class'=>'btn btn-success']) . Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax'=>false, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
						],
						'{export}',
						'{toggleData}'
					],
		 'panel' => [
						'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-road"></i> Delivery Route </h3>',
						'type'=>'primary',
						'after'=>Html::a(''),
						'showFooter'=>false
					],	
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            'route_name',
            'start_position',
            'end_position',
            [
				'attribute'=>'status',
				'value' =>function ($model, $key, $index) {						
						return  DefaultSetting::getConfigByValue($model->status,"price");	
						},
				'class' => 'kartik\grid\EditableColumn',
				'editableOptions' => [
								'header' => 'Route Status', 
								'inputType' => Editable::INPUT_DROPDOWN_LIST,
								'data'=>ArrayHelper::map($status, 'value', 'name'), // any list of values
								'options' => ['class'=>'form-control', 'prompt'=>'Select Status...'],
							],
				'filterType' => GridView::FILTER_SELECT2,
				'filter' => ArrayHelper::map($status,'value', 'name'),
				 'filterWidgetOptions' => [
					'pluginOptions' => ['allowClear' => true],
				],
				'filterInputOptions' => ['placeholder' => 'Select Status'], 
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
			],
             [
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
														'data-pjax' => '0',
												]);                             

											},

						],
					'urlCreator' => function ($action, $model, $key, $index) {
								
								if ($action === 'view') {
									$url = Yii::$app->urlManager->createUrl(['/route/view',"id" => $model->id,]);
									return $url;
								}
								if ($action === 'update') {
									$url = Yii::$app->urlManager->createUrl(['/route/update',"id" => $model->id,]); 
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

