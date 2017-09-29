<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\CowMilkBillingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cow-milk-billing-search">

    <?php $form = ActiveForm::begin([ 'id' => 'cow-milk-billing-search-horizontal',
										'type' => ActiveForm::TYPE_HORIZONTAL,
										'formConfig' => ['labelSpan' => 3, 
										'deviceSize' => ActiveForm::SIZE_LARGE],
										'action' => ['index'],
										'method' => 'get',
									]); ?>

   <div class="row">
	<div class="col-sm-5">
	  <?=$form->field($model, 'bill_cycle')->widget(DatePicker::classname(), [
				'options' => ['placeholder' => 'Select Bill Month ...'],
				'pluginOptions' => [
				'autoclose'=>true,
				 'startView'=>'year',
                  'minViewMode'=>'months',
				'format' => 'yyyy-mm'
			]
			]); ?>
	</div>
	<div class="col-sm-5">
		<?= $form->field($model, 'user_id')->widget(Select2::classname(), [
				'data' => ArrayHelper::map($users,'user_id', 'staff'),
				'options' => ['placeholder' => 'Select Customer ...'],
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

	 </div>   
	<div class="col-sm-2">
		<div class="form-group">
		
			<?= Html::submitButton('Search Bill',['class' => 'btn btn-primary',
                'data-confirm' => 'Are you sure you want to generate this bill?'
            ]) ?>
		
		</div>
	</div>
	</div>
    <?php ActiveForm::end(); ?>

</div>
