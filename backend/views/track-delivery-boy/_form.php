<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\widgets\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\TrackDeliveryBoy */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="track-delivery-boy-form row">

  <div class="col-sm-1"></div>
<div class="col-sm-10">
   <?php $form = ActiveForm::begin([ 'id' => 'track-delivery-boy-form-horizontal',
										'type' => ActiveForm::TYPE_HORIZONTAL,
										'formConfig' => ['labelSpan' => 2, 
										'deviceSize' => ActiveForm::SIZE_LARGE],
									]); ?>

   <?=$form->field($model, 'date_time')->widget(DateTimePicker::classname(), [
			'options' => ['placeholder' => 'Enter Date Time ...'],
			'pickerButton' => ['icon' => 'time'],
			'pluginOptions' => [
				'autoclose'=>true,
				'format' => 'yyyy-mm-dd hh:ii:ss'
			]
		]); ?>

    <?= $form->field($model, 'position', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-pushpin"></i>']]])->textInput(['maxlength' => true]) ?>

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

    <div class="form-group">
	<div class="pull-right">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	</div>

    <?php ActiveForm::end(); ?>

</div>
<div class="col-sm-1"></div>
</div>