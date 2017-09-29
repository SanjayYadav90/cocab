<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\AccountStatement */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="account-statement-form row">
<div class="col-sm-1"></div>
<div class="col-sm-10">
     <?php $form = ActiveForm::begin([ 	'id' => 'statement-form-horizontal',
										'type' => ActiveForm::TYPE_HORIZONTAL,
										'formConfig' => ['labelSpan' => 3, 
										'deviceSize' => ActiveForm::SIZE_SMALL]
									]); 
									
	$payment_option = ['Cash'=>'Cash','Cheque'=>'Cheque','Prevoius Balance'=>'Prevoius Balance','Reverse'=>'Reverse','Discount'=>'Discount','Waive Off'=>'Waive Off','Online'=>'Online'];
	
	$payment_type = ['Dr.'=>'Dr.','Cr.'=>'Cr.'];
	$payment_status = ['Received'=>'Received','Pending'=>'Pending','Cheque Bounce'=>'Cheque Bounce','Failed'=>'Failed'];						
	?>

     <?php /*= $form->field($model, 'user_id', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-user"></i>']]])
						->dropDownList( ArrayHelper::map($users, 'id', 'username'),[
                    'prompt'=>'--- Select User Name ---'] )->label('Customer Mobile')*/  ?>
					
	<?= $form->field($model, 'user_id')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($staff,'user_id', 'staffMobile'),
			'options' => ['placeholder' => 'Select a user mobile ...'],
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
		
		<?= $form->field($model, 'mobile', [
							'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-user"></i>']]])->textInput(['disabled'=>true,'id'=>'user-mobile'])->label('User Name') ?>
		
		<?= $form->field($model, 'address', [
							'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-user"></i>']]])->textarea(['disabled'=>true,'id'=>'user-address','rows' => '3'])->label('User Address') ?>

	<?= $form->field($model, 'amount', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-pushpin"></i>']]])->textInput() ?>

	  <?= $form->field($model, 'type',[
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-credit-card"></i>']]])->dropDownList( $payment_type,[
                    'prompt'=>'--- Select Payment Type ---'] ) ?>

    <?= $form->field($model, 'payment_mode',[
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-credit-card"></i>']]])->dropDownList( $payment_option,[
                    'prompt'=>'--- Select Payment mode ---'] ) ?>
		
	<div id="offer">
	
		 <?= $form->field($model, 'bank_name', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-pushpin"></i>']]])->textInput() ?>
						
		 <?= $form->field($model, 'bank_branch', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-pushpin"></i>']]])->textInput() ?>
						
		 <?= $form->field($model, 'cheque_number', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-pushpin"></i>']]])->textInput() ?>
	</div>

	 <?= $form->field($model, 'payment_status',[
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-credit-card"></i>']]])->dropDownList( $payment_status,[
                    'prompt'=>'--- Select Payment Status ---'] ) ?>
  
	<?= $form->field($model, 'delivery_boy_id')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($d_boys,'user_id', 'staff'),
			'options' => ['placeholder' => 'Select a D Boy name ...'],
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
	
	<?=$form->field($model, 'transaction_date')->widget(DatePicker::classname(), [
			'options' => ['placeholder' => 'Enter Transaction date ...'],
			'pluginOptions' => [
				'autoclose'=>true,
				'format' => 'yyyy-mm-dd'
			]
		]); ?>

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
		$('#accountstatement-payment_mode').on('change', function() {
			
		 if(this.value == 'Cheque')
		  {
			  $('#offer').show();
			   //myBlurFunction();
		  }
		  else
		  {
			  $('#offer').hide();
		  }
		});
		
		var flag = document.getElementById("accountstatement-payment_mode");
		var offerStatus = flag.options[flag.selectedIndex].value;
		
		if(offerStatus == 'Cheque')
		{
			$('#offer').show();
		}
		else
		  {
			  $('#offer').hide();
		  }
		  
		$('#accountstatement-user_id').on('change', function() {
			var url = '<?=Url::toRoute('subscription/userdetails',true)?>';
			$.ajax({
			  method: "POST",
			  url: url,
			  data: { user_id: this.value }
			})
			  .done(function( mobile ) {
				$('#user-mobile').val(mobile);
			  });
			  
			var url = '<?=Url::toRoute('subscription/useraddressdetails',true)?>';
			$.ajax({
			  method: "POST",
			  url: url,
			  data: { user_id: this.value }
			})
			  .done(function( address ) {
				$('#user-address').val(address);
			  });
			  
		});
		
		var user_id = document.getElementById("accountstatement-user_id").value;
		var url = '<?=Url::toRoute('subscription/userdetails',true)?>';
			$.ajax({
			  method: "POST",
			  url: url,
			  data: { user_id: user_id }
			})
			  .done(function( mobile ) {
				$('#user-mobile').val(mobile);
			  });
			  
			 var url = '<?=Url::toRoute('subscription/useraddressdetails',true)?>';
			$.ajax({
			  method: "POST",
			  url: url,
			  data: { user_id: user_id }
			})
			  .done(function( address ) {
				$('#user-address').val(address);
			  });  
  }); 
  

</script>
<style>
#offer{display:none;}
</style>
