<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductFilter */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-filter-form row">

    <div class="col-sm-1"></div>
<div class="col-sm-10">
   <?php $form = ActiveForm::begin([ 'id' => 'product-filter-form-horizontal',
										'type' => ActiveForm::TYPE_HORIZONTAL,
										'formConfig' => ['labelSpan' => 2, 
										'deviceSize' => ActiveForm::SIZE_LARGE],
									]); ?>

    <?= $form->field($model, 'name', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-pushpin"></i>']]])->textInput(['maxlength' => true]) ?>

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