<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\Staff;
use backend\models\Role;
use backend\models\Users;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\StaffSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customer/Staff';
$this->params['breadcrumbs'][] = $this->title;


?>
<?php 
		$title='Change Device Id ';
		include( Yii::getAlias('@modaldialogs'));
	?>
<div class="staff-index">

    <h1><?php //echo Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    
   <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax'=>true,
		'toolbar' => [
						['content'=>
							Html::a('<i class="glyphicon glyphicon-plus"></i> ',['create'], ['type'=>'button',
								'title'=>'Add New Customer/Staff', 'class'=>'btn btn-success']) . Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax'=>false, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
						],
						'{export}',
						'{toggleData}'
					],
		 'panel' => [
						'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-user"></i> Customer/Staff </h3>',
						'type'=>'primary',
						'before'=>Html::a('<i class="glyphicon glyphicon-pencil"></i> Change Device Id ',['changedeviceid'], ['type'=>'button',
								'title'=>'Changed Device Id for Delivery boy', 'class'=>'btn btn-success'])/* Html::button('<i class="glyphicon glyphicon-floppy-saved"></i>Change Device Id ', ['type'=>'button',
								'title'=>'Changed Device Id for Delivery boy', 'class'=>'btn btn-primary','onclick'=>'js:OpenModalDialog("'.Url::to(['changedeviceid']).'")'])*/,
						'after'=>Html::a(''),
						'showFooter'=>false
					],		
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
					
                    [
						'attribute'=>'first_name',
						'label'=>'User Name',
						'value' => 'staff',
						'width'=>'180px',
						'filterType'=>GridView::FILTER_SELECT2,
							'filter'=>ArrayHelper::map(Staff::find()->orderBy('first_name','last_name')->asArray()->all(), 'first_name', 'first_name'), 
							'filterWidgetOptions'=>[
								'pluginOptions'=>['allowClear'=>true],
							],
							'filterInputOptions'=>['placeholder'=>'Any User'],
							'format'=>'raw'
					],
					
					[
						'attribute'=>'main_mobile',
						'label'=>'Mobile',
						'value' => 'users.username',
						'width'=>'150px',
						'filterType'=>GridView::FILTER_SELECT2,
						'filter'=>ArrayHelper::map(Users::find()->orderBy('username')->asArray()->all(), 'id', 'username'), 
						'filterWidgetOptions'=>[
							'pluginOptions'=>['allowClear'=>true],
						],
						'filterInputOptions'=>['placeholder'=>'Any Mobile'],
						'format'=>'raw'
					],
					/* [
						'attribute'=>'phone',
						'label'=>'Alt Phone',
						'vAlign'=>'middle',
						'width'=>'150px',
						'filterType'=>GridView::FILTER_SELECT2,
						'filter'=>ArrayHelper::map(Staff::find()->orderBy('phone')->asArray()->all(), 'phone', 'phone'), 
						'filterWidgetOptions'=>[
							'pluginOptions'=>['allowClear'=>true],
						],
						'filterInputOptions'=>['placeholder'=>'Alt Mobile'],
						'format'=>'raw'
					], */
					[
						'attribute'=>'email',
						'vAlign'=>'middle',
						'filterType'=>GridView::FILTER_SELECT2,
						'filter'=>ArrayHelper::map(Staff::find()->orderBy('email')->asArray()->all(), 'email', 'email'), 
						'filterWidgetOptions'=>[
							'pluginOptions'=>['allowClear'=>true],
						],
						'filterInputOptions'=>['placeholder'=>'Any Email'],
						'format'=>'raw'
					],
                    [
						'attribute'=>'address_id',
						'value' => 'displayAddress',
					], 
					[
						'attribute'=>'staff_type',
						'value' => 'staffType',
						/* 'filterType'=>GridView::FILTER_SELECT2,
							'filter'=>ArrayHelper::map($m_users, 'role', 'role'), 
							'filterWidgetOptions'=>[
								'pluginOptions'=>['allowClear'=>true],
							],
							'filterInputOptions'=>['placeholder'=>'Any Role'],
							'format'=>'raw' */
					],
					[
						'attribute'=>'route',
						'value' => 'users.routeName',
						/* 'filterType'=>GridView::FILTER_SELECT2,
							'filter'=>ArrayHelper::map($m_users, 'role', 'role'), 
							'filterWidgetOptions'=>[
								'pluginOptions'=>['allowClear'=>true],
							],
							'filterInputOptions'=>['placeholder'=>'Any Role'],
							'format'=>'raw' */
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
