<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\AccountStatementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Account Statements';
$this->params['breadcrumbs'][] = $this->title;

$payment_option = ['Cash'=>'Cash','Cheque'=>'Cheque','Prevoius Balance'=>'Prevoius Balance','Reverse'=>'Reverse','Discount'=>'Discount','Waive Off'=>'Waive Off','Online'=>'Online'];			
$payment_type = ['Dr.'=>'Dr.','Cr.'=>'Cr.'];
$payment_status = ['Received'=>'Received','Pending'=>'Pending','Cheque Bounce'=>'Cheque Bounce','Failed'=>'Failed'];	
?>
<div class="account-statement-index">
    
  <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'pjax'=>true,
		'toolbar' => [
						['content'=>
							Html::a('<i class="glyphicon glyphicon-plus"></i> ',['create'], ['type'=>'button',
								'title'=>'Add New A/C Statement', 'class'=>'btn btn-success']) . Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax'=>false, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
						],
						'{export}',
						'{toggleData}'
					],
		 'panel' => [
						'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-user"></i> Account Statement </h3>',
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
						'value' => 'userName',
						'filterType'=>GridView::FILTER_SELECT2,
						'filter'=>ArrayHelper::map($staff, 'user_id', 'staff'), 
						'filterWidgetOptions'=>[
							'pluginOptions'=>['allowClear'=>true],
						],
						'filterInputOptions'=>['placeholder'=>'Any Customer'],
						'format'=>'raw',
					],
					
					[
						'attribute'=>'mobile',
						'vAlign'=>'middle',
						'value' => 'user.username',
						'filterType'=>GridView::FILTER_SELECT2,
						'filter'=>ArrayHelper::map($staff, 'user_id', 'staffMobile'), 
						'filterWidgetOptions'=>[
							'pluginOptions'=>['allowClear'=>true],
						],
						'filterInputOptions'=>['placeholder'=>'Any Mobile'],
						'format'=>'raw',
					],
					[
						'attribute'=>'transaction_date',
						'vAlign'=>'middle',
						'filterType'=> \kartik\grid\GridView::FILTER_DATE, 
						'filterWidgetOptions' => [
								'options' => ['placeholder' => 'Select Transaction date'],
								'pluginOptions' => [
									'format' => 'yyyy-mm-dd',
									'todayHighlight' => true,
									'autoclose' => true,
								]
						],
						'group' => false,
						'format' => 'html',
						

						
					], 
            'amount',
			[
                'attribute'=>'payment_mode',
                'filter'=>$payment_option
            ],
			[
                'attribute'=>'payment_status',
                'filter'=>$payment_status
            ],
			[
                'attribute'=>'type',
                'filter'=>$payment_type
            ],
			'order_id',
			[
				'attribute'=>'delivery_boy_id',
				'vAlign'=>'middle',
				'value'=>function ($model, $key, $index, $widget) {
						return $model->deliveryBoyName;
						//return isset($model->deliveryBoy->staff) ? $model->deliveryBoy->staff->first_name.' '.$model->deliveryBoy->staff->last_name : '' ;
							},
					'filterType'=>GridView::FILTER_SELECT2,
					'filter'=>ArrayHelper::map($d_boys, 'user_id', 'staff'), 
					'filterWidgetOptions'=>[
						'pluginOptions'=>['allowClear'=>true],
					],
					'filterInputOptions'=>['placeholder'=>'Any Boy'],
					'format'=>'raw',
					
			],
            [
				'attribute'=>'created_at',
				'vAlign'=>'middle',
				'value' =>function ($model, $key, $index) {						
						return  $date_time = date('Y-m-d H:i:s', $model->created_at);	
						},
			], 

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
