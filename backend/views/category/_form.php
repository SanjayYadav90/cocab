<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin([ 'id' => 'category-form-horizontal',
										'type' => ActiveForm::TYPE_HORIZONTAL,
										'formConfig' => ['labelSpan' => 3, 
										'deviceSize' => ActiveForm::SIZE_LARGE],
										'options'=>['enctype'=>'multipart/form-data'],
									]); ?>

    <?= $form->field($model, 'name', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-user"></i>']]
						])->textInput() ?>

     <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

   <?=  $form->field($model, 'image')->widget(FileInput::classname(), [
			'name'=>'image',
            'options'=>['accept'=>'image/*'],
            'pluginOptions'=>[
				'allowedFileExtensions'=>['jpg','gif','png'],
				'showUpload' => false,	
				'maxFileSize'=> '1024',
			]
		]);  ?>
		
	<?= $form->field($model, 'status', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-globe"></i>']]])
						->dropDownList( ArrayHelper::map($cat_status, 'value', 'name'),[
                    'prompt'=>'--- Select Status ---'])->label('Status')  ?>


    <div class="form-group">
        <div class="col-sm-3"></div>
	   <div class="col-sm-9">
	   <?php
		
		if(isset($model->image) && !empty($model->image))
		{ ?>
			<img src= "<?= 'http://'.$_SERVER['SERVER_NAME'].$model->image?>" style="width:100%;" alt="category Image" />
	
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
