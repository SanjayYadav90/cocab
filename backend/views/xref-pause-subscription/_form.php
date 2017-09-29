<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\XrefPauseSubscription */
/* @var $form yii\widgets\ActiveForm */
$type = ['edit'=>'Edit','pause'=>'Pause'];
?>

<div class="xref-pause-subscription-form row">
<div class="col-sm-1"></div>
<div class="col-sm-10">
    <?php $form = ActiveForm::begin([ 'id' => 'xref-pause-subscription-form-horizontal',
										'type' => ActiveForm::TYPE_HORIZONTAL,
										'formConfig' => ['labelSpan' => 3, 
										'deviceSize' => ActiveForm::SIZE_LARGE]
									]); ?>

   

   <?= $form->field($model, 'user_id')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($users,'user_id', 'staffMobile'),
			'options' => ['placeholder' => 'Select a user mobile ...'],
			'pluginOptions' => [
				'allowClear' => true,
				
			],
			'size' => Select2::MEDIUM,
			'addon' => [
				'prepend' => [
					'content' => '<i class="glyphicon glyphicon-phone"></i>'
				],
			],
		])->label('User Mobile');  ?>
		<?php $model->mobile = $model->isNewRecord ? $model->mobile :($model->users->staff ? $model->users->staff->staff : '') ?>
	<?= $form->field($model, 'mobile', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-user"></i>']]])->textInput(['disabled'=>true,'id'=>'user-mobile'])->label('User Name') ?>

		<?php $model->product_id = $model->isNewRecord ? 1 : $model->product_id ?>				
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
			

		<?= $form->field($model, 'type', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-globe"></i>']]])
						->dropDownList($type,[
                    'prompt'=>'--- Select Type ---'])->label('Type')  ?>

	<?= $form->field($model, 'quantity', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-pushpin"></i>']]])->textInput() ?>


	<?php $model->start_date = $model->isNewRecord ? date('Y-m-d') : $model->start_date ?>
	<?php $model->end_date = $model->isNewRecord ? date('Y-m-d') : $model->end_date ?>	
	<?=$form->field($model, 'start_date')->widget(DatePicker::classname(), [
			'options' => ['placeholder' => 'Enter Date ...'],
			    'attribute2'=>'end_date',
				'type' => DatePicker::TYPE_RANGE,
				'pluginOptions' => [
				'autoclose'=>true,
				'format' => 'yyyy-mm-dd'
			]
		])->label('Date From'); ?>
		
		<?php /* echo $form->field($model, 'end_date')->widget(DatePicker::classname(), [
			'options' => ['placeholder' => 'Enter End date ...'],
			'pluginOptions' => [
				'autoclose'=>true,
				'format' => 'yyyy-mm-dd'
			]
		]);  */?>

	
	
	

    <?php //echo $form->field($model, 'coupon_id')->textInput() ?>


    <div class="form-group">
	<div class="pull-right">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	</div>

    <?php ActiveForm::end(); ?>
	</div>
<div class="col-sm-1"></div>
</div>

<script type="text/javascript">
	 $(document).ready(function(){
		var type = $('#xrefpausesubscription-type').val();
		if(type == 'pause'){
			$('#xrefpausesubscription-quantity').prop("readonly", true);
		}
		else{
			$('#xrefpausesubscription-quantity').prop("readonly", false);
		}
		 
		$('#xrefpausesubscription-user_id').on('change', function() {
			var url = '<?=Url::toRoute('subscription/userdetails',true)?>';
			$.ajax({
			  method: "POST",
			  url: url,
			  data: { user_id: this.value }
			})
			  .done(function( mobile ) {
				//$('#user-mobile').prop("disabled", false);
				$('#user-mobile').val(mobile);
				//$('#user-mobile').prop("disabled", false);
			  });
		});
		
		$('#xrefpausesubscription-type').on('change', function() {
			
				//$('#user-mobile').prop("disabled", false);
				var type = $('#xrefpausesubscription-type').val();
				if(type == 'pause'){
					$('#xrefpausesubscription-quantity').val(0);
					$('#xrefpausesubscription-quantity').prop("readonly", true);
				}
				else{
					$('#xrefpausesubscription-quantity').val('');
					$('#xrefpausesubscription-quantity').prop("readonly", false);
				}
				//$('#user-mobile').prop("disabled", false);
		});
		
  }); 
  

</script>
