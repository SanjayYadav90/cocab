<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DeliverySlotNameSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Delivery Slot Names';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="delivery-slot-name-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'pjax'=>true,
		'toolbar' => [
						['content'=>
							Html::a('<i class="glyphicon glyphicon-plus"></i> ',['create'], ['type'=>'button',
								'title'=>'Add New Delivery Slot Name', 'class'=>'btn btn-success']) . Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax'=>false, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
						],
						'{export}',
						'{toggleData}'
					],
		 'panel' => [
						'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-user"></i> Delivery Slot Name </h3>',
						'type'=>'primary',
						//'before'=>,
						'after'=>Html::a(''),
						'showFooter'=>false
					],	
        'columns' => [
             ['class' => 'kartik\grid\SerialColumn'],

            'delivery_slot',
            'delivery_charge',
            [
				'class'=>'kartik\grid\BooleanColumn',
				'attribute'=>'status',
			],
            [
				'attribute'=>'created_at',
				'value' =>function ($model, $key, $index) {						
						return  $date_time = date('Y-m-d H:i:s', $model->created_at);	
						},
			],
            // 'updated_at',

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