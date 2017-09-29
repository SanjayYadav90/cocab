<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\form\ActiveForm;


?>

<div class="Change-dboy-form" style="width:100%;">
	
    <?php $form = ActiveForm::begin(['fieldConfig' => ['autoPlaceholder'=>true]]); ?>
	
	<div >
	
	<?php
	$d_boys_list = ArrayHelper::map($d_boys, 'user_id', 'staff'); ?>
	<?= $form->field($model, 'delivery_boy_id', [
					'addon' => ['prepend' => ['content'=>'D Boy']]])->dropDownList(
                $d_boys_list,
                [ 
                    'prompt'=>'--- Select Delivery Boy ---',    
                ]
        ) ->label('Delivery Boy')  ?>

	</div>
<div class="modal-footer clearfix">

            <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Discard</button>

            <?= Html::submitButton($model->isNewRecord ? ' <span class="glyphicon glyphicon-floppy-disk"></span> Update' : ' <span class="glyphicon glyphicon-floppy-disk"></span> Update', ['class' => $model->isNewRecord ? 'btn btn-primary pull-right' : 'btn btn-primary pull-right']) ?> 
        </div>
    <?php ActiveForm::end(); ?>


</div>
