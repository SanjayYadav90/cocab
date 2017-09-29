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
									]); ?>

   <div class="row">
	<div class="col-sm-5">
	  <?=$form->field($model, 'bill_cycle')->widget(DatePicker::classname(), [
				'options' => ['placeholder' => 'Select Bill Month ...'],
				'pluginOptions' => [
				'autoclose'=>true,
				'startView'=>'year',
                'minViewMode'=>'months',
				'format' => 'yyyy-mm',
				'endDate' => "0d"
			]
			]); ?>
	</div>
	<div class="col-sm-5">
		<?= $form->field($model, 'user_id')->widget(Select2::classname(), [
				'data' => ArrayHelper::map($users,'user_id', 'staffMobile'),
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
			])->label('User Mobile');  ?>

	 </div>   
	<div class="col-sm-2">
		
	</div>
	</div>
	
	<div class="row">
	<div class="col-sm-5">

	 <?= $form->field($model, 'mobile', [
							'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-user"></i>']]])->textInput(['disabled'=>true,'id'=>'user-mobile'])->label('User Name') ?>
			 
	</div>
	<div class="col-sm-5">
	<?= $form->field($model, 'address', [
							'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-user"></i>']]])->textarea(['disabled'=>true,'id'=>'user-address','rows' => '3'])->label('User Address') ?>

	</div>
	<div class="col-sm-2">
	<div class="form-group">
		
			<?= Html::submitButton('Generate Bill',['class' => 'btn btn-primary',
                'data-confirm' => 'Are you sure you want to generate this bill?'
            ]) ?>
		
		</div>
	</div>
	</div>
    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
	 $(document).ready(function(){
		$('#cowmilkbilling-user_id').on('change', function() {
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
			  
			var url = '<?=Url::toRoute('subscription/useraddressdetails',true)?>';
			$.ajax({
			  method: "POST",
			  url: url,
			  data: { user_id: this.value }
			})
			  .done(function( address ) {
				//$('#user-mobile').prop("disabled", false);
				$('#user-address').val(address);
				//$('#user-mobile').prop("disabled", false);
			  });
		});
		
		var user_id = document.getElementById("cowmilkbilling-user_id").value;
		var url = '<?=Url::toRoute('subscription/userdetails',true)?>';
			$.ajax({
			  method: "POST",
			  url: url,
			  data: { user_id: user_id }
			})
			  .done(function( mobile ) {
				//$('#user-mobile').prop("disabled", false);
				$('#user-mobile').val(mobile);
				//$('#user-mobile').prop("disabled", false);
			  });
			  
			var url = '<?=Url::toRoute('subscription/useraddressdetails',true)?>';
			$.ajax({
			  method: "POST",
			  url: url,
			  data: { user_id: user_id }
			})
			  .done(function( address ) {
				//$('#user-mobile').prop("disabled", false);
				$('#user-address').val(address);
				//$('#user-mobile').prop("disabled", false);
			  });
		
  }); 
  

</script>
