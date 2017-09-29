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
use backend\models\DeliverySearch;
use common\models\User;
use backend\models\Role;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\CowMilkBillingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cow Milk Billings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cow-milk-billing-index">

    <?php //echo $this->render('_search', ['model' => $searchModel,'users'=> $users]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
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
					'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-cloud"></i> Cow Milk Billings </h3>',
					'type'=>'primary',
					'before'=>Html::a(''),
					'after'=>Html::a(''),
					'showFooter'=>false
				],	
        'columns' => [
				
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'class'=>'kartik\grid\ExpandRowColumn',
				'width'=>'50px',
				'value'=>function ($model, $key, $index, $widget) {
					return GridView::ROW_COLLAPSED;
				},
				  'detail'=>function ($model, $key, $index, $widget) {
					  //print_r($model);
					 $searchModel = new DeliverySearch();
					 $searchModel->product_id = 1;
					 $searchModel->user_id = $model->user_id;
					 $searchModel->subscription_id = $model->subscription_id;
					 $d_date = $model->bill_cycle;
					 $d_date = date('Y-m',strtotime($d_date));
					 $delivery_status = DefaultSetting::find()->where(['type'=>'delivery'])->all();
						$users = Staff::find()
							->innerJoinWith('users', 'Staff.user_id = Users.id')
							->andWhere(['user.status' => User::STATUS_ACTIVE])
							->andWhere(['user.role' => Role::getRole('CUSTOMER')])
							->all();
						$d_boys = Staff::find()
							->innerJoinWith('users', 'Staff.user_id = Users.id')
							->andWhere(['user.status' => User::STATUS_ACTIVE])
							->andWhere(['user.role' => Role::getRole('DELIVERY BOY')])
							->all();
					 $dataProvider = $searchModel->search5(Yii::$app->request->queryParams);
					 $dataProvider->query->andFilterWhere(['between','delivery_date', $model->start_date,$model->end_date]);
					 $dataProvider->query->andFilterWhere(['<>','quantity', -1]);
					return Yii::$app->controller->renderPartial('_delivery-details', ['dataProvider'=>$dataProvider,'delivery_status'=>$delivery_status,'users'=>$users,'d_boys'=>$d_boys]);
				},  
				'headerOptions'=>['class'=>'kartik-sheet-style'] ,
				'expandOneOnly'=>true
			],
            'id',
            //'subscription_id',
            [
				'attribute'=>'user_id',
				'vAlign'=>'middle',
				'value'=>function ($model, $key, $index, $widget) { 
						return Html::a(isset($model->user->staff) ? $model->user->staff->first_name.' '.$model->user->staff->last_name : '',  
							'#', 
							['title'=>'View user detail'.$model->subscription_id, 'onclick'=>'alert("This will open the user page.\n\nDisabled for now!")']);
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
				'value'=>function ($model, $key, $index, $widget) { 
						return Html::a(isset($model->user) ? $model->user->username : '',  
							'#', 
							['title'=>'View user detail'.$model->subscription_id, 'onclick'=>'alert("This will open the user page.\n\nDisabled for now!")']);
					},
					'filterType'=>GridView::FILTER_SELECT2,
					'filter'=>ArrayHelper::map($users, 'user_id', 'staffMobile'), 
					'filterWidgetOptions'=>[
						'pluginOptions'=>['allowClear'=>true],
					],
					'filterInputOptions'=>['placeholder'=>'Any User'],
					'format'=>'raw'
			],
			[
			 'attribute'=>'bill_cycle',
			 'format'=>['DateTime','php:M-Y'],
			 'filterType'=> \kartik\grid\GridView::FILTER_DATE, 
						'filterWidgetOptions' => [
								'options' => ['placeholder' => ' Bill Cycle Month'],
								'pluginOptions' => [
									'autoclose'=>true,
									 'startView'=>'year',
									  'minViewMode'=>'months',
									'format' => 'yyyy-mm'
								]
						],

			],
            'delivered_quantity',
            'sub_total',
            // 'referral_discount',
            // 'voucher_discount',
            // 'tax',
            // 'bill_amount',
            // 'previous_due_amount',
             'net_payable_amount',
             'billing_gen_date',
            // 'created_by',
            // 'updated_by',
            // 'created_at',
            // 'updated_at',
			[
				'attribute'=>'delivery_boy_id',
				'vAlign'=>'middle',
				'value'=>function ($model, $key, $index, $widget) { 
						return Html::a(isset($model->deliveryBoy) ? $model->deliveryBoy : '',  
							'#', 
							['title'=>'View user detail'.$model->user_id, 'onclick'=>'alert("This will open the delivery boy page.\n\nDisabled for now!")']);
					},
					'filterType'=>GridView::FILTER_SELECT2,
					'filter'=>ArrayHelper::map($d_boys, 'user_id', 'staff'), 
					'filterWidgetOptions'=>[
						'pluginOptions'=>['allowClear'=>true],
					],
					'filterInputOptions'=>['placeholder'=>'Any D Boy'],
					'format'=>'raw'
			],

            [	
				'class' => 'kartik\grid\ActionColumn',
				'vAlign'=>'middle',
				'template' => '{view} {view_bill} ',
				'buttons' =>[
				 
				  'view' =>function ($url, $model) {     
									return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
											'title' => Yii::t('yii', 'View'),
											'data-pjax' => '0',
									]);                             

								},
				  'view_bill' =>function ($url, $model) {     
									return Html::a('<span class="glyphicon glyphicon-print"></span>', $url, [
											'title' => Yii::t('yii', 'View Bill'),
											//'onclick' => 'js:OpenModalDialog("'.$url.'")',
											'data-pjax' => '0',
											'target' => '_blank'
									]);                             

								}, 
				
					
				],
				'urlCreator' => function ($action, $model, $key, $index) {
					
					if ($action === 'view') {
						$url = Yii::$app->urlManager->createUrl(['/cow-milk-billing/view',"id" => $model->id,]);
						return $url;
					}
					 if ($action === 'view_bill') {
						$url = Yii::$app->urlManager->createUrl(['/cow-milk-billing/view-bill',"id" => $model->id,]); // your own url generation logic
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
