<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;


$this->title = 'Sms Queues';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="sms-queue-index">
<?php
	$title='SMS Queues';
	include( Yii::getAlias('@modaldialogs')); 
		
	$gridColumns =[
		['class' => 'kartik\grid\SerialColumn'],
					[
						'attribute'=>'to_phone',
						'vAlign'=>'middle',
					],
					[
						'attribute' => 'message_text', 
						'vAlign'=>'middle',
						'format' => 'html',
					],

					[
						'attribute'=>'created_at',
						'vAlign'=>'middle',
						'value' =>function ($model, $key, $index) {						
								return  $date_time = date('Y-m-d H:i:s', $model->created_at);	
								},
					],
					/* [
						'attribute'=>'date_sent',
						'vAlign'=>'middle',
					],
					[
						'attribute'=>'last_attempt',
						'vAlign'=>'middle',
					], */
					[
						'attribute' => 'user_id', 
						'vAlign'=>'middle',
						'value'=>'user.username',
					],
					
					[
						'class' => 'kartik\grid\ActionColumn',
						'vAlign'=>'middle',
						'template' => '{view} ',
						'buttons' =>[
										'view' =>function ($url, $model) {     
													return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', '#', [
															'title' => Yii::t('yii', 'View'),
															'onclick' => 'js:OpenModalDialog("'.$url.'")',															
															'data-pjax' => '0',
													]);                             
												},
									],
						'urlCreator' => function ($action, $model, $key, $index) {
											if ($action === 'view') {
													$url = Yii::$app->urlManager->createUrl(['/smsqueue/view',"id" => $model->id,]);
													return $url;
												}
										}
					
					],
					
			]
		
?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'pjax'=>true,
		'toolbar' => [
						['content'=> Html::button('Compose New Message <i class="fa fa-envelope"></i> ', ['type'=>'button',
								'title'=>'Compose New Message', 'class'=>'btn btn-success', 'onclick'=>'js:OpenModalDialog("'.Url::to(['create','src'=>'sms']).'")']).
							Html::a('<i class="glyphicon glyphicon-refresh"></i>', ['index'], ['data-pjax'=>false, 'class' => 'btn btn-default', 'title'=>'Reset SMS Messages'])
						],
						'{export}',
						'{toggleData}'
					],
		 'panel' => [
						'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> SMS Messages </h3>',
						'type'=>'primary',
						'before'=>Html::a(''),
						'after'=>Html::a(''),
						'showFooter'=>false
					],
        'columns' => $gridColumns ,
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
