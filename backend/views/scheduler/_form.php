<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use backend\models\DefaultSetting;
use kartik\widgets\TimePicker;
use kartik\form\ActiveField;

/* @var $this yii\web\View */
/* @var $model backend\models\Scheduler */
/* @var $form yii\widgets\ActiveForm */
$sender_type = DefaultSetting::find()->where(['type'=>'sender_type'])->orderby('name ASC')->all(); 
$frequency_type = DefaultSetting::find()->where(['type'=>'frequency_type'])->orderby('name ASC')->all(); 
$scheduler_status = DefaultSetting::find()->where(['type'=>'scheduler_status'])->orderby('name ASC')->all(); 
$send_msg_type = DefaultSetting::find()->where(['type'=>'send_msg_type'])->orderby('name ASC')->all();
?>

<div class="scheduler-form row">
<div class="col-sm-1"></div>
<div class="col-sm-10">
    <?php $form = ActiveForm::begin([ 	'id' => 'scheduler-form-horizontal',
										'type' => ActiveForm::TYPE_HORIZONTAL,
										'formConfig' => ['labelSpan' => 3, 
										'deviceSize' => ActiveForm::SIZE_SMALL]
									]); ?>

    <?= $form->field($model, 'send_msg_type')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($send_msg_type, 'value', 'name'),
			'options' => ['placeholder' => 'Select a Send Msg Type ...','multiple' => false],
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
	<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'template_cat', [
					'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-book"></i>']]])->dropDownList(
                ArrayHelper::map($template_cat,'value', 'name'),
                [
                    'prompt'=>'--- Select Template Category ---',
                    'onchange'=>'
					$.get( "'.Url::toRoute('/scheduler/template').'", { template: $(this).val() } )
						.done(function( data ) {
							$( "#'.Html::getInputId($model, 'template_id').'" ).html( data );
						}
					);
					'       
                ]
        ) ->label('Template Category')  ?>
	
	<?= $form->field($model, 'template_id', [
					'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-book"></i>']]])
					->dropDownList($model->isNewRecord ? ['prompt' => '---- Select Template ----'] : ArrayHelper::map($template_name, 'id', 'name'))->label('Template Name')  ?>
	
	
	<?= $form->field($model, 'template_body', [
							'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-user"></i>']]])->textarea(['disabled'=>true,'id'=>'template-body','rows' => '4'])->label('Template Body') ?>

	<?= $form->field($model, 'sender_type', [
					'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-book"></i>']]])
					->dropDownList( ArrayHelper::map($sender_type, 'value', 'name'),[
                    'prompt'=>'--- Select Sender Type ---',
                    'onchange'=>'
					$.get( "'.Url::toRoute('/scheduler/sender-list').'", { type: $(this).val() } )
						.done(function( data ) {
							$( "#'.Html::getInputId($model, 'sender_list').'" ).html( data );
						}
					);
					'       
                ])  ?>

   
	<?= $form->field($model, 'sender_list')->widget(Select2::classname(), [
			'data' => $model->isNewRecord ? ['prompt' => '---- Select Sender ----'] :$sender_list,
			'options' => ['placeholder' => 'Select a Sender List ...','multiple' => true],
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
		
	<?= $form->field($model, 'frequency_type')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($frequency_type, 'value', 'name'),
			'options' => ['placeholder' => 'Select a Frequency Type ...','multiple' => false],
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

    <?=$form->field($model, 'start_date')->widget(DatePicker::classname(), [
				'options' => ['placeholder' => 'Select Start Date ...'],
				'pluginOptions' => [
					'autoclose'=>true,
					'format' => 'yyyy-mm-dd',
					'todayHighlight' => true,
					'startDate' => date("yyyy-MM-dd H:i:s"),
				]
			]); ?>

    <?= $form->field($model, 'time',['hintType' => ActiveField::HINT_SPECIAL,
			'hintSettings' => [
				'iconBesideInput' => true,
				'onLabelClick' => true,
				'onLabelHover' => true,
				'onIconClick' => true,
				'onIconHover' => true,
				'title' => '<i class="glyphicon glyphicon-info-sign"></i> Note'
						],])->widget(TimePicker::classname(), [
			'pluginOptions' => [
				'showSeconds' => true,
				'showMeridian' => false,
				'minuteStep' => 1,
				'secondStep' => 5,
				],
				
	])->hint('Time Format is 24 hours.'); ?>
	
	<?= $form->field($model, 'status')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($scheduler_status, 'value', 'name'),
			'options' => ['placeholder' => 'Select a Scheduler Status ...','multiple' => false],
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
		 
		var template_category = document.getElementById("scheduler-template_cat").value;
		$('#scheduler-template_cat').on('change', function() {
			$('#template-body').val("");
		});
		$('#scheduler-template_id').on('change', function() {
			var url = '<?=Url::toRoute('scheduler/templatedetails',true)?>';
			$.ajax({
			  method: "POST",
			  url: url,
			  data: { template_id: this.value }
			})
			  .done(function( template ) {
				$('#template-body').val(template);
			  });
		});
		
		var template_id = document.getElementById("scheduler-template_id").value;
		var url = '<?=Url::toRoute('scheduler/templatedetails',true)?>';
			$.ajax({
			  method: "POST",
			  url: url,
			  data: { template_id: template_id }
			})
			  .done(function( template ) {
				$('#template-body').val(template);
			  });
  }); 
  

</script>