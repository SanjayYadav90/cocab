<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\DeliveryAnalytics */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="delivery-analytics-form row">

   <div class="col-sm-1"></div>
<div class="col-sm-10">
   <?php $form = ActiveForm::begin([ 'id' => 'delivery-analytics-form-horizontal',
										'type' => ActiveForm::TYPE_HORIZONTAL,
										'formConfig' => ['labelSpan' => 2, 
										'deviceSize' => ActiveForm::SIZE_LARGE],
									]); ?>

    <?= $form->field($model, 'distance', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-pushpin"></i>']]])->textInput() ?>

    <?= $form->field($model, 'total_time', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-pushpin"></i>']]])->textInput() ?>

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

