<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use backend\models\DefaultSetting;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\SmsTemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sms Templates';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="sms-template-index">

    <?php 
		$title='SMS Templates';
		include( Yii::getAlias('@modaldialogs'));
		if (Yii::$app->getSession()->hasFlash('error')) {
		echo '<div class="alert alert-danger">'.Yii::$app->getSession()->getFlash('error').'</div>';
	}
	?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'pjax'=>true,
		'toolbar' => [
						['content'=>
							Html::button('<i class="glyphicon glyphicon-plus"></i> ', ['type'=>'button',
								'title'=>'Add New Template', 'class'=>'btn btn-success', 'onclick'=>'js:OpenModalDialog("'.Url::to(['create']).'")']) . Html::a('<i class="glyphicon glyphicon-refresh"></i>', ['index'], ['data-pjax'=>false, 'class' => 'btn btn-default', 'title'=>'Reset Templates'])
						],
						'{export}',
						'{toggleData}'
					],
		 'panel' => [
						'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-envelope"></i> SMS Templates </h3>',
						'type'=>'primary',
						/* 'before'=>,
						'after'=>Html::a(''), */
						'showFooter'=>false
					],		
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
					[
						'attribute' => 'name', 
						'vAlign'=>'middle',	
					],
					[
						'attribute' => 'body', 
						'vAlign'=>'middle',	
					],
					[
						'attribute' => 'template_cat', 
						'vAlign'=>'middle',	
					],
					[
						'class' => 'kartik\grid\BooleanColumn',
						'attribute' => 'type', 
						'vAlign'=>'middle',	
						 'trueLabel' => 'Admin',
						 'falseLabel' => 'System',
						 'trueIcon' =>'Admin',
						 'falseIcon' => 'System'
					],
					[
						
						'class' => 'kartik\grid\ActionColumn',
						'vAlign'=>'middle',
						'template' => ' {view} {update}  {delete}',
						'buttons' =>[
						 'view' =>function ($url, $model) {     
											return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', "#", [
													'title' => Yii::t('yii', 'View'),
													'onclick' => 'js:OpenModalDialog("'.$url.'")',
													'data-pjax' => '0',
											]);                             

										},
							'update' => function ($url, $model, $key) {
							
											return  Html::a('<span class="glyphicon glyphicon-pencil"></span>','#', [
													'title' => Yii::t('yii', 'Update'),
													'onclick' => 'js:OpenModalDialog("'.$url.'")',
													'data-pjax' => '0',
												]) ;
										},
							 'delete' => function ($url, $model, $key) {
							
											return $model->type == 1 ? Html::a('<span class="glyphicon glyphicon-trash"></span>',$url, [
													'title' => Yii::t('yii', 'Delete'),
													'data-pjax' => '0',
												]) : '';
										}, 
									],
						 'urlCreator' => function ($action, $model, $key, $index) {
													if ($action === 'view') {
													$url = Yii::$app->urlManager->createUrl(['/sms-template/view',"id" => $model->id,]); // your own url generation logic
													return $url;
													}
													if ($action === 'update') {
													$url = Yii::$app->urlManager->createUrl(['/sms-template/update',"id" => $model->id,]); // your own url generation logic
													return $url;
													}
													if ($action === 'delete') {
													$url = Yii::$app->urlManager->createUrl(['/sms-template/delete',"id" => $model->id,]); // your own url generation logic
													return $url;
													}
												 }
					
					],
					//['class' => 'kartik\grid\CheckboxColumn']
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
