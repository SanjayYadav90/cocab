<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\form\ActiveForm;


?>

<div class="Change-status-form" style="width:100%;">
	
    <?php $form = ActiveForm::begin(['fieldConfig' => ['autoPlaceholder'=>true]]); ?>
	
	<div >
	
	<?php
	$status = ArrayHelper::map($delivery_status, 'value', 'name'); ?>
	<?= $form->field($model, 'isdeliver', [
					'addon' => ['prepend' => ['content'=>'Status']]])->dropDownList(
                $status,
                [ 
                    'prompt'=>'--- Select Delivery Status ---',    
                ]
        ) ->label('Delivery Status')  ?>

	</div>
<div class="modal-footer clearfix">

            <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Discard</button>

            <?= Html::submitButton($model->isNewRecord ? ' <span class="glyphicon glyphicon-floppy-disk"></span> Update' : ' <span class="glyphicon glyphicon-floppy-disk"></span> Update', ['class' => $model->isNewRecord ? 'btn btn-primary pull-right' : 'btn btn-primary pull-right']) ?> 
        </div>
    <?php ActiveForm::end(); ?>


</div>
