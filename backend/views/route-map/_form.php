<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\RouteMap */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="route-map-form row">
<div class="col-sm-1"></div>
<div class="col-sm-10">
    <?php $form = ActiveForm::begin([ 'id' => 'route-map-form-horizontal',
										'type' => ActiveForm::TYPE_HORIZONTAL,
										'formConfig' => ['labelSpan' => 2, 
										'deviceSize' => ActiveForm::SIZE_LARGE],
									]); ?>

   <?= $form->field($model, 'route_id')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($route,'id', 'route_name'),
			'options' => ['placeholder' => 'Select a Route Name ...'],
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

    <?= $form->field($model, 'sequence', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-pushpin"></i>']]])->textInput() ?>
	
	<?= $form->field($model, 'user_id')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($users,'user_id', 'staffMobile'),
			'options' => ['placeholder' => 'Select a Customer Mobile ...'],
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
<?php $model->mobile = $model->isNewRecord ? $model->mobile : ($model->user->staff ? $model->user->staff->staff : ''); ?>
	<?= $form->field($model, 'mobile', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-user"></i>']]])->textInput(['disabled'=>true,'id'=>'user-mobile'])->label('User Name'); ?>

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
		$('#routemap-user_id').on('change', function() {
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