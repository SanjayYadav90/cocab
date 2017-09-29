<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\City */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="city-form">

     <?php $form = ActiveForm::begin([ 'id' => 'city-form-horizontal',
										'type' => ActiveForm::TYPE_HORIZONTAL,
										'formConfig' => ['labelSpan' => 3, 
										'deviceSize' => ActiveForm::SIZE_LARGE]
									]); ?>

    <?= $form->field($model, 'city', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-map-marker"></i>']]])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'city2state')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($state,'id', 'state'),
			'options' => ['placeholder' => 'Select a state name ...'],
			'pluginOptions' => [
				'allowClear' => true,
				
			],
			'size' => Select2::MEDIUM,
			'addon' => [
				'prepend' => [
					'content' => '<i class="glyphicon glyphicon-globe"></i>'
				],
			],
		]);  ?>
	<?php $model->active = $model->isNewRecord ? 1 : $model->active; ?>
    <?= $form->field($model, 'active', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-ok-sign"></i>']]])->dropDownList(
                                ArrayHelper::map($status,'value', 'name'),
                                ['prompt'=>'Select...']) ?>

    <div class="form-group">
	 <div class="pull-right">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	</div>

    <?php ActiveForm::end(); ?>

</div>
