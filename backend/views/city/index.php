<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cities';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="city-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		
		'pjax'=>true,
		'id' => 'grid',
		'toolbar' => [
						['content'=>
							Html::a('<i class="glyphicon glyphicon-plus"></i> ',['create'], ['type'=>'button',
								'title'=>'Add New City', 'class'=>'btn btn-success']) . Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax'=>false, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
						],
						'{export}',
						'{toggleData}'
					],
		'panel' => [
						'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-cloud"></i> City List </h3>',
						'type'=>'primary',
						'before'=>Html::a(''),
						'after'=>Html::a(''),
						'showFooter'=>false
					],	
        'columns' => [
				
            ['class' => 'kartik\grid\SerialColumn'],
					
                   'city',
					[
						'attribute'=>'city2state',
						'vAlign'=>'middle',
						'value' => 'city2state0.state',
					
							'filterType'=>GridView::FILTER_SELECT2,
							'filter'=>ArrayHelper::map($state, 'id', 'state'), 
							'filterWidgetOptions'=>[
								'pluginOptions'=>['allowClear'=>true],
							],
							'filterInputOptions'=>['placeholder'=>'Any State'],
							'format'=>'raw'
					],
					[
						'class'=>'kartik\grid\BooleanColumn',
						'attribute'=>'active', 
						'vAlign'=>'middle'
					], 	
			
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
							
						],
					'urlCreator' => function ($action, $model, $key, $index) {
								if ($action === 'view') {
									$url = Yii::$app->urlManager->createUrl(['/city/view',"id" => $model->id,]);
									return $url;
								}
								if ($action === 'update') {
									$url = Yii::$app->urlManager->createUrl(['/city/update',"id" => $model->id,]); // your own url generation logic
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
