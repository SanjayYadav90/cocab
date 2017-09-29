<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\widgets\Select2;
use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductBrandName */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-brand-name-form row">
<div class="col-sm-1"></div>
<div class="col-sm-10">
   <?php $form = ActiveForm::begin([ 'id' => 'product-brand-name-form-horizontal',
										'type' => ActiveForm::TYPE_HORIZONTAL,
										'formConfig' => ['labelSpan' => 2, 
										'deviceSize' => ActiveForm::SIZE_LARGE],
										'options'=>['enctype'=>'multipart/form-data'],
									]); ?>


    <?= $form->field($model, 'name', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-pushpin"></i>']]])->textInput(['maxlength' => true]) ?>

    <?=  $form->field($model, 'image')->widget(FileInput::classname(), [
			'name'=>'image',
            'options'=>['accept'=>'image/*'],
            'pluginOptions'=>[
				'allowedFileExtensions'=>['jpg','gif','png'],
				'showUpload' => false,	
				'maxFileSize'=> '1024',
			]
		]);  ?>
	<?php $model->status = $model->isNewRecord ? 1 : $model->status; ?>
   <?= $form->field($model, 'status', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-globe"></i>']]])
						->dropDownList( ArrayHelper::map($status, 'value', 'name'),[
                    'prompt'=>'--- Select Status ---'])  ?>

 
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
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div></div>

    <?php ActiveForm::end(); ?>
	</div>
<div class="col-sm-1"></div>
</div>
