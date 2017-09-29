<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AreaDiscountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Area Discount';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="area-discount-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax'=>true,
		'toolbar' => [
						['content'=>
							Html::a('<i class="glyphicon glyphicon-plus"></i> ',['create'], ['type'=>'button',
								'title'=>'Add New Area wise Discount', 'class'=>'btn btn-success']) . Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax'=>false, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
						],
						'{export}',
						'{toggleData}'
					],
		 'panel' => [
						'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-road"></i> All Area Wise Discount </h3>',
						'type'=>'primary',
						'after'=>Html::a(''),
						'showFooter'=>false
					],	
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
				'attribute'=>'area_name',		
			],
            'area_discount',
           
             [
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
														'data-pjax' => '0',
												]);                             

											},

						],
					'urlCreator' => function ($action, $model, $key, $index) {
								
								if ($action === 'view') {
									$url = Yii::$app->urlManager->createUrl(['/area-discount/view',"id" => $model->id,]);
									return $url;
								}
								if ($action === 'update') {
									$url = Yii::$app->urlManager->createUrl(['/area-discount/update',"id" => $model->id,]); 
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