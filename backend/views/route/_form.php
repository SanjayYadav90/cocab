<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\widgets\Select2;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model backend\models\Route */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="route-form row">

 <div class="col-sm-1"></div>
<div class="col-sm-10">
   <?php $form = ActiveForm::begin([ 'id' => 'route-form-horizontal',
										'type' => ActiveForm::TYPE_HORIZONTAL,
										'formConfig' => ['labelSpan' => 2, 
										'deviceSize' => ActiveForm::SIZE_LARGE],
									]); ?>

    <?= $form->field($model, 'route_name', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-road"></i>']]])->textInput(['maxlength' => true]) ?>

    <?php  echo $form->field($model, 'start_position', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-pushpin"></i>']]])->textInput(['maxlength' => true])  ?>
						
	<?php
			/* echo $form->field($model, 'start_position')->widget('\pigolab\locationpicker\CoordinatesPicker' , [
				'key' => 'AIzaSyA7DawxfplFJpxkFx8GZ7jgR5fXa7aUWNA' ,   // optional , Your can also put your google map api key
				'valueTemplate' => '{latitude},{longitude}' , // Optional , this is default result format
				'options' => [
					'style' => 'width: 100%; height: 400px',  // map canvas width and height
				] ,
				'enableSearchBox' => true , // Optional , default is true
				'searchBoxOptions' => [ // searchBox html attributes
					'style' => 'width: 300px;', // Optional , default width and height defined in css coordinates-picker.css
				],
				'mapOptions' => [
					// set google map optinos
					'rotateControl' => true,
					'scaleControl' => true,
					'streetViewControl' => true,
					'mapTypeId' => new JsExpression('google.maps.MapTypeId.ROADMAP'),
					'heading'=> 90,
					'tilt' => 45 ,
					'mapTypeControl' => true,
					'mapTypeControlOptions' => [
						  'style'    => new JsExpression('google.maps.MapTypeControlStyle.HORIZONTAL_BAR'),
						  'position' => new JsExpression('google.maps.ControlPosition.TOP_CENTER'),
					]
				],
				'clientOptions' => [
					'radius'    => 0,
					'location' => [
						'latitude'  => 28.7126612,
						'longitude' => 77.1340976,
					],
					'zoom' => 16,
				]
			]); */
		?>

    <?php  echo $form->field($model, 'end_position', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-pushpin"></i>']]])->textInput(['maxlength' => true])  ?>

	<?php
			/* echo $form->field($model, 'end_position')->widget('\pigolab\locationpicker\CoordinatesPicker' , [
				'key' => 'AIzaSyA7DawxfplFJpxkFx8GZ7jgR5fXa7aUWNA' ,   // optional , Your can also put your google map api key
				'valueTemplate' => '{latitude},{longitude}' , // Optional , this is default result format
				'options' => [
					'style' => 'width: 100%; height: 400px',  // map canvas width and height
				] ,
				'enableSearchBox' => true , // Optional , default is true
				'searchBoxOptions' => [ // searchBox html attributes
					'style' => 'width: 300px;', // Optional , default width and height defined in css coordinates-picker.css
				],
				'mapOptions' => [
					// set google map optinos
					'rotateControl' => true,
					'scaleControl' => false,
					'streetViewControl' => true,
					'mapTypeId' => new JsExpression('google.maps.MapTypeId.ROADMAP'),
					'heading'=> 90,
					'tilt' => 45 ,
					'mapTypeControl' => true,
					'mapTypeControlOptions' => [
						  'style'    => new JsExpression('google.maps.MapTypeControlStyle.HORIZONTAL_BAR'),
						  'position' => new JsExpression('google.maps.ControlPosition.TOP_CENTER'),
					]
				],
				'clientOptions' => [
					'radius'    => 0,
					'location' => [
						'latitude'  => 28.7126612,
						'longitude' => 77.1340976,
					],
					'zoom' => 16,
				]
			]); */
		?>


   <?= $form->field($model, 'delivery_boy_id')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($d_boys,'user_id', 'first_name'),
			'options' => ['placeholder' => 'Select a D Boy ...'],
			'pluginOptions' => [
				'allowClear' => true,
				
			],
			'size' => Select2::MEDIUM,
			'addon' => [
				'prepend' => [
					'content' => '<i class="glyphicon glyphicon-user"></i>'
				],
			],
		]);  ?>

    <?php $model->status = $model->isNewRecord ? 1 : $model->status; ?>
   <?= $form->field($model, 'status', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-globe"></i>']]])
						->dropDownList( ArrayHelper::map($status, 'value', 'name'),[
                    'prompt'=>'--- Select Status ---'])  ?>

    <div class="form-group">
	<div class="pull-right">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	</div>

    <?php ActiveForm::end(); ?>

</div>
<div class="col-sm-1"></div>
</div>
