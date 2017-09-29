<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\tabs\TabsX;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\Staff */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="staff-form row">
<div class="col-sm-1"></div>
<div class="col-sm-10">
<?php $staff_type = ArrayHelper::map($role, 'id', 'role_name');

      
    $form = ActiveForm::begin([ 'id' => 'staff-form-horizontal',
										'type' => ActiveForm::TYPE_HORIZONTAL,
										'formConfig' => ['labelSpan' => 3, 
										'deviceSize' => ActiveForm::SIZE_LARGE],
										'options'=>['enctype'=>'multipart/form-data']
									]); ?>
	<?php if(!$model->isNewRecord) { 
	$delivery_boy = ArrayHelper::map($delivery_boys, 'id', 'userName');
    $distributor = ArrayHelper::map($distributors, 'id', 'userName');
	
	?>
		<?= $form->field($mod_users, 'role', [
							'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-screenshot"></i>']]])
							->dropDownList($staff_type,['prompt'=>'Choose...','disabled' => true])->label('User Type')  ?>
		
		<?= $form->field($mod_users, 'mobile', [
							'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-phone"></i>']]
							])->textInput(['readonly' => true]) ?>
							
		<?= $form->field($mod_users, 'username', [
							'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-user"></i>']]])->textInput(['readonly' => true, 'id'=>'user-name']) ?>
		 
		<?php if($mod_users->role == 2 || $mod_users->role == 3 ){ ?>
		
		<?= $form->field($mod_users, 'distributor_id', [
							'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-user"></i>']]])
							->dropDownList($distributor,['prompt'=>'Choose...'])->label('Distributor')  ?>
			
		<?php } 
			if($mod_users->role == 2 ) {?>
			
		 
			 <?= $form->field($mod_users, 'delivery_boy_id', [
							'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-user"></i>']]])
							->dropDownList($delivery_boy,['prompt'=>'Choose...'])->label('Delivery Boy')  ?>
			<?php } ?>
			
			<?php if($mod_users->role != 2) { $mod_users->password_hash = '' ?>
		 
			
			<?= $form->field($mod_users, 'password_hash', [
							'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-phone"></i>']]
							])->textInput()->hint('Enter new Password if you want to changed otherewise leave it blank.'); ?>
			<?php } ?>
		
	<?php }else { ?>
	
	<?= $form->field($mod_users, 'role', [
							'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-screenshot"></i>']]])
							->dropDownList($staff_type,['prompt'=>'Choose...'])->label('User Type')  ?>
		
		<?= $form->field($mod_users, 'mobile', [
							'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-phone"></i>']]
							])->textInput() ?>
							
		<?= $form->field($mod_users, 'username', [
							'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-user"></i>']]])->textInput(['readonly' => true, 'id'=>'user-name']) ?>
		 
	
	<?php } ?>
	
	<?= $form->field($mod_users, 'route_id')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($route,'id', 'route_name'),
			'options' => ['placeholder' => 'Select a user Route Name ...'],
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
	<?= $form->field($model, 'first_name', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-user"></i>']]
						])->textInput() ?>

		<?= $form->field($model, 'last_name', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-user"></i>']]
						])->textInput() ?>
						
		<?= $form->field($model, 'phone', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-earphone"></i>']]
						])->textInput() ?>
		<?= $form->field($model, 'email', [
						'addon' => ['prepend' => ['content'=>'@']]])->textInput() ?>

		<?= $form->field($mod_address, 'address1', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-home"></i>']]])->textInput() ?>
		  
		  
		<?= $form->field($mod_address, 'address2', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-home"></i>']]])->textInput() ?>
		
		<?php $country_lists = ArrayHelper::map($country, 'id', 'country'); ?>
		
		<?= $form->field($mod_address, 'country_id', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-globe"></i>']]])->dropDownList(
					$country_lists
					/* [
						'prompt'=>'--- Select Country ---',
						'onchange'=>'
							$.get( "'.Url::toRoute('/organisation/country').'", { id: $(this).val() } )
								.done(function( data ) {
									$( "#'.Html::getInputId($mod_address, 'state_id').'" ).html( data );
								}
							);
						'    
					] */
			) ->label('Country')  ?>
		
		<?= $form->field($mod_address, 'state_id', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-globe"></i>']]])
						->dropDownList( ArrayHelper::map($state, 'id', 'state') )->label('State')  ?>
		
		<?= $form->field($mod_address, 'city', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-globe"></i>']]])->textInput() ?>

		<?= $form->field($mod_address, 'pincode', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-pushpin"></i>']]])->textInput() ?>

		<?=  $form->field($model, 'profile_pic')->widget(FileInput::classname(), [
			'name'=>'profile_pic',
            'options'=>['accept'=>'image/*'],
            'pluginOptions'=>[
				'allowedFileExtensions'=>['jpg','gif','png'],
				'showUpload' => true,	
				'maxFileSize'=> '1024',
			]
		]);  ?>
		<?php
		
		if(isset($model->profile_pic) && !empty($model->profile_pic) && file_exists(Yii::getAlias('@webroot') . '/uploads/' . $model->profile_pic))
		{ ?>
			<img src= "<?= Yii::$app->request->baseUrl . '/uploads/' . $model->profile_pic?>" style="height: 70px;" alt="Logo" />
	
		<?php	} ?>
		<?php //echo $form->field($model, 'profile_pic')->fileInput(['maxlength' => true]) ?>


    <div class="form-group">
	<div class="pull-right">
     
		<?= Html::submitButton($model->isNewRecord ? '<span class="glyphicon glyphicon-plus"></span> Create' :
		'<span class="glyphicon glyphicon-floppy-disk"></span> Update', 
		['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>
	</div>
    <?php ActiveForm::end(); ?>
	</div>
<div class="col-sm-1"></div>
</div>

<script type="text/javascript">
	var x = document.getElementById("staff-form-horizontal");
	x.addEventListener("keyup", myFocusFunction, true);
	x.addEventListener("blur", myBlurFunction, true);

	function myFocusFunction() {
		var text_value = document.getElementById("users-mobile").value;
		document.getElementById("user-name").value = text_value.replace(/[^a-zA-Z0-9]+/ig, "");
	}

	 function myBlurFunction() {
		var text_value = document.getElementById("users-mobile").value;
		document.getElementById("user-name").value = text_value.replace(/[^a-zA-Z0-9]+/ig, "");
	} 
	$(document).ready(function(){
    
		 document.getElementById("user-name").value = document.getElementById("users-mobile").value;
  });

</script>
