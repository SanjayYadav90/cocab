<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\widgets\FileInput;


/* @var $this yii\web\View */
/* @var $model backend\models\Products */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-form row">
<div class="col-sm-1"></div>
<div class="col-sm-10">
    <?php $form = ActiveForm::begin([ 'id' => 'products-form-horizontal',
										'type' => ActiveForm::TYPE_HORIZONTAL,
										'formConfig' => ['labelSpan' => 3, 
										'deviceSize' => ActiveForm::SIZE_SMALL],
										'options'=>['enctype'=>'multipart/form-data'],
									]); ?>
					
   <?= $form->field($model, 'name', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-gift"></i>']]
						])->textInput() ?>

    <?= $form->field($model, 'description', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-globe"></i>']]])->textarea(['rows' => 3]) ?>
	
	<?= $form->field($model, 'price', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-gift"></i>']]
						])->textInput(/* ['type' => 'number'] */) ?>
	<?php //				$form->field($model, 'price', ['inputOptions' => ['value' => Yii::$app->formatter->asDecimal($model->price)]])?>
	<?=  $form->field($model, 'image')->widget(FileInput::classname(), [
			'name'=>'image',
            'options'=>['accept'=>'image/*'],
            'pluginOptions'=>[
				'allowedFileExtensions'=>['jpg','gif','png'],
				'showUpload' => false,	
				'maxFileSize'=> '1024',
			]
		]);  ?>
		<?= $form->field($model, 'cat_id', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-globe"></i>']]])
						->dropDownList( ArrayHelper::map($cat_list, 'id', 'name'),[
                    'prompt'=>'--- Select Category ---'])->label('Category')  ?>

    <?php //echo  $form->field($model, 'image_name')->textInput(['maxlength' => true]) ?>
	
	
	 <?= $form->field($model, 'brand_id')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($brands,'id', 'name'),
			'options' => ['placeholder' => 'Select a Brand Name ...'],
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
		
		<?= $form->field($model, 'delivery_slot_id')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($slots,'id', 'delivery_slot'),
			'options' => ['placeholder' => 'Select a Delivery Slot Name ...'],
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
		
		<?= $form->field($model, 'product_filter')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($prod_filter,'name', 'name'),
			'options' => ['placeholder' => 'Select a Product Filter Name ...','multiple' => true],
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
		
		<?= $form->field($model, 'status', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-globe"></i>']]])
						->dropDownList( ArrayHelper::map($product_status, 'value', 'name'),[
                    'prompt'=>'--- Select Status ---'])->label('Status')  ?>
	
	
       <div class="form-group">
	   <div class="col-sm-3"></div>
	   <div class="col-sm-9">
	   <?php
		
		if(isset($model->image) && !empty($model->image))
		{ ?>
			<img src= "<?= 'http://'.$_SERVER['SERVER_NAME'].$model->image?>" style="width:100%;" alt="Product Image" />
	
		<?php	} ?>
		</div>
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
