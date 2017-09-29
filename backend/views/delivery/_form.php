<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\tabs\TabsX;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\Delivery */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="delivery-form row ">
<div class="col-sm-1"></div>
<div class="col-sm-10">
    <?php $form = ActiveForm::begin([ 'id' => 'delivery-form-horizontal',
										'type' => ActiveForm::TYPE_HORIZONTAL,
										'formConfig' => ['labelSpan' => 3, 
										'deviceSize' => ActiveForm::SIZE_LARGE]
									]); ?>

   
	
	<?= $form->field($model, 'user_id')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($users,'user_id', 'first_name'),
			'options' => ['placeholder' => 'Select a user name ...'],
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
						
	<?= $form->field($model, 'product_id')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($products,'id', 'name'),
			'options' => ['placeholder' => 'Select a product name ...'],
			'pluginOptions' => [
				'allowClear' => true,
				
			],
			'size' => Select2::MEDIUM,
			'addon' => [
				'prepend' => [
					'content' => '<i class="glyphicon glyphicon-gift"></i>'
				],
			],
		]);  ?>
						
	<?= $form->field($model, 'quantity', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-pushpin"></i>']]])->textInput() ?>

	
	<?= $form->field($model, 'delivered', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-pushpin"></i>']]])->textInput() ?>


	<?=$form->field($model, 'delivery_date')->widget(DatePicker::classname(), [
			'options' => ['placeholder' => 'Select date ...'],
			'pluginOptions' => [
				'autoclose'=>true,
				'format' => 'yyyy-mm-dd'
			]
		]); ?>

    <?= $form->field($model, 'isdeliver')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($delivery_status,'value', 'name'),
			'options' => ['placeholder' => 'Select Status ...'],
			'pluginOptions' => [
				'allowClear' => true,
				
			],
			'size' => Select2::MEDIUM,
			'addon' => [
				'prepend' => [
					'content' => '<i class="glyphicon glyphicon-gift"></i>'
				],
			],
		]);  ?>
		
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
	
	 <?php if($model->isNewRecord){ ?> 
	
	<?= $form->field($model, 'address_id')->textInput() ?>
	 <?= $form->field($model, 'delivery_boy_id')->textInput() ?>
	 <?= $form->field($model, 'area')->textInput(['maxlength' => true]) ?>
	  <?= $form->field($model, 'subscription_id')->textInput() ?>
	
	<?php } ?>

    <div class="form-group">
	<div class="pull-right">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	 </div>

    <?php ActiveForm::end(); ?>

</div>
<div class="col-sm-1"></div>
</div>
