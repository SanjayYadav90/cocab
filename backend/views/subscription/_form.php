<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\tabs\TabsX;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use backend\models\DefaultSetting;


/* @var $this yii\web\View */
/* @var $model backend\models\Subscription */
/* @var $form yii\widgets\ActiveForm */
if($model->isNewRecord) {
	$status = DefaultSetting::find()->where(['type'=>'subscription','value' =>1])->All();
}
else{
	$status = DefaultSetting::find()->where(['type'=>'subscription'])->all();
}
?>

<div class="subscription-form row">
<div class="col-sm-1"></div>
<div class="col-sm-10">
    <?php $form = ActiveForm::begin([ 'id' => 'subscription-form-horizontal',
										'type' => ActiveForm::TYPE_HORIZONTAL,
										'formConfig' => ['labelSpan' => 3, 
										'deviceSize' => ActiveForm::SIZE_LARGE]
									]); ?>

	<?php /* $form->field($model, 'user_id', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-user"></i>']]])
						->dropDownList( ArrayHelper::map($users, 'id', 'username'),[
                    'prompt'=>'--- Select User Name ---'] )->label('Customer Mobile') */  ?>
					
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

	
	
	

     <?php $model->status = $model->isNewRecord ? 1 : $model->status; ?>
   <?= $form->field($model, 'status', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-globe"></i>']]])
						->dropDownList( ArrayHelper::map($status, 'value', 'name'),[
                    'prompt'=>'--- Select Status ---'])  ?>


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
		$('#subscription-user_id').on('change', function() {
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
		
  }); 
  

</script>
