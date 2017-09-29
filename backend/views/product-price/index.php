<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use kartik\editable\Editable;
use backend\models\DefaultSetting;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductPriceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Product Prices';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php 
		$title='Deliveries';
		include( Yii::getAlias('@modaldialogs'));
	?>
<div class="product-price-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax'=>true,
		'id' => 'grid',
		'toolbar' => [
						['content'=>
							Html::a('<i class="glyphicon glyphicon-plus"></i> ',['create'], ['type'=>'button',
								'title'=>'Add New Price', 'class'=>'btn btn-success']) . Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax'=>false, 'class' => 'btn btn-default', 'title'=>'Reset Grid'])
						],
						'{export}',
						'{toggleData}'
					],
		'panel' => [
						'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-cloud"></i> Product Price List </h3>',
						'type'=>'primary',
						'before'=>Html::a(''),
						'after'=>Html::a(''),
						'showFooter'=>false
					],	
        'columns' => [
				//['class' => 'kartik\grid\CheckboxColumn'],
				
            ['class' => 'kartik\grid\SerialColumn'],
					
                   
					[
						'attribute'=>'product_id',
						'vAlign'=>'middle',
						//'value' => 'product.name',
						//'width'=>'160px',
						'value'=>function ($model, $key, $index, $widget) { 
								return Html::a($model->product->name,  
									'#', 
									['title'=>'View Product detail', 'onclick'=>'alert("This will open the Product page.\n\nDisabled for now!")']);
							},
							'filterType'=>GridView::FILTER_SELECT2,
							'filter'=>ArrayHelper::map($products, 'id', 'name'), 
							'filterWidgetOptions'=>[
								'pluginOptions'=>['allowClear'=>true],
							],
							'filterInputOptions'=>['placeholder'=>'Any Product'],
							'format'=>'raw'
					],
					[
						'class' => 'kartik\grid\EditableColumn',			
						'attribute'=>'quantity',
						'editableOptions' => [
							'header' => 'Product Quantity',
							//'asPopover'=>false,
							'inputType' => \kartik\editable\Editable::INPUT_TEXT,
							],
						'vAlign'=>'middle',
					],
					[
						'class' => 'kartik\grid\EditableColumn',			
						'attribute'=>'unit',
						'editableOptions' => [
							'header' => 'Product Unit', 
							'inputType' => \kartik\editable\Editable::INPUT_TEXT,
							],
						'vAlign'=>'middle',
					],
			
					'mrp',
					
					[
						'attribute' => 'offer_unit',
						'value' => 'offerStatus',
						'filter' => Html::activeDropDownList($searchModel, 'offer_unit', ArrayHelper::map(DefaultSetting::find()->where(['type'=>'offer_unit'])->all(), 'value', 'name'),['class'=>'form-control','prompt' => 'Select Offer Style']),
					],
					[
						'attribute' => 'offer_flag',
						'value' => 'offerStyle',
						'filter' => Html::activeDropDownList($searchModel, 'offer_flag', ArrayHelper::map(DefaultSetting::find()->where(['type'=>'offer_flag'])->all(), 'value', 'name'),['class'=>'form-control','prompt' => 'Select Offer Status']),
					],
					'offer_price',
					'discounted_mrp',
					[
						'attribute' => 'status',
						'value' => 'priceStatus',
						'filter' => Html::activeDropDownList($searchModel, 'status', ArrayHelper::map(DefaultSetting::find()->where(['type'=>'price'])->all(), 'value', 'name'),['class'=>'form-control','prompt' => 'Select Status']),
					],
					
						
			
             [
				'attribute'=>'created_at',
				'vAlign'=>'middle',
				
				'value' =>function ($model, $key, $index) {
						
						return  $date_time = date('Y-m-d H:i:s', $model->created_at);	
						},
			], 
			/* [
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
														//'onclick' => 'js:OpenModalDialog("'.$url.'")',
														'data-pjax' => '0',
												]);                             

											},
							
								
							   /* 'routecomplete' =>function ($url, $model) {     
												return Html::a('<span class="glyphicon glyphicon-off"></span>', $url, [
														'title' => Yii::t('yii', 'Route Completed'),
														//'onclick' => 'js:OpenModalDialog("'.$url.'")',
														'data-pjax' => '0',
												]);                             

											}, */
						],
					'urlCreator' => function ($action, $model, $key, $index) {
								/* if ($action === 'addstop') {
									$url = Yii::$app->urlManager->createUrl(['/route/addstop',"id" => $model->id,]); // your own url generation logic
									return $url;
								} */
								if ($action === 'view') {
									$url = Yii::$app->urlManager->createUrl(['/product-price/view',"id" => $model->id,]);
									return $url;
								}
								if ($action === 'update') {
									$url = Yii::$app->urlManager->createUrl(['/product-price/update',"id" => $model->id,]); // your own url generation logic
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


	