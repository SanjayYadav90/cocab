<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\SmsTemplate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sms-template-form">

   <?php $form = ActiveForm::begin([ 	'id' => 'sms-template-form-horizontal',
										'fieldConfig' => ['autoPlaceholder'=>true]
									]); ?>
	<?= $form->field($model, 'template_cat')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($template_cat,'name', 'name'),
			'options' => ['placeholder' => 'Select a Template Category ...'],
			'pluginOptions' => [
				'allowClear' => true,
				
			],
			'size' => Select2::MEDIUM,
			'addon' => [
				'prepend' => [
					'content' => '<i class="glyphicon glyphicon-road"></i>'
				],
			],
		]);  ?>
	
	<?php if ($model->isNewRecord )
	{ ?>
    <?= $form->field($model, 'name', [
					'addon' => ['prepend' => ['content'=>'Name']]
					])->textInput(['maxlength' => 64 ]) ?>
	<?php }
	else
	{
	?>
	<?= $form->field($model, 'name', [
					'addon' => ['prepend' => ['content'=>'Name']]
					])->textInput(['maxlength' => 64 ,'readonly'=>'true']) ?>
	<?php } ?>
	
	 <?= $form->field($model, 'type')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($message_template,'value', 'name'),
			'options' => ['placeholder' => 'Select a Template Type ...'],
			'pluginOptions' => [
				'allowClear' => true,
				
			],
			'size' => Select2::MEDIUM,
			'addon' => [
				'prepend' => [
					'content' => '<i class="glyphicon glyphicon-road"></i>'
				],
			],
		]);  ?>
    <?= $form->field($model, 'body')->textarea(['rows' => 4,'maxlength' => 140,'placeholder'=>'Enter your message' ]) ?>

    <div class="modal-footer clearfix">

            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Discard</button>

            <?= Html::submitButton($model->isNewRecord ? ' <span class="fa fa-plus"></span> Create' : ' <span class="glyphicon glyphicon-floppy-disk"></span> Update', ['class' => $model->isNewRecord ? 'btn btn-success pull-left' : 'btn btn-primary pull-left']) ?> 
        </div>

    <?php ActiveForm::end(); ?>

</div>
