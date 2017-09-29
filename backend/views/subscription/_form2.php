<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\tabs\TabsX;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\Subscription */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="subscription-form2">

    <?php $form = ActiveForm::begin([ 'id' => 'subscription-form2-horizontal',
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



	<?=$form->field($model, 'start_date')->widget(DatePicker::classname(), [
			'options' => ['placeholder' => 'Enter Start date ...'],
			'pluginOptions' => [
				'autoclose'=>true,
				'format' => 'yyyy-mm-dd'
			]
		]); ?>
		
		<?=$form->field($model, 'end_date')->widget(DatePicker::classname(), [
			'options' => ['placeholder' => 'Enter End date ...'],
			'pluginOptions' => [
				'autoclose'=>true,
				'format' => 'yyyy-mm-dd'
			]
		]); ?>

	
	
	

    <?php //echo $form->field($model, 'coupon_id')->textInput() ?>


    <div class="form-group">
	<div class="pull-right">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	</div>

    <?php ActiveForm::end(); ?>

</div>
