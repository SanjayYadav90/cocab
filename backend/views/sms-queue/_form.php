<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\SmsQueue */
/* @var $form yii\widgets\ActiveForm */

$this->registerCssFile(Yii::$app->request->baseUrl.'/admin-lte/dist/css/token-input-facebook.min.css');
$this->registerJsFile(Yii::$app->request->baseUrl.'/admin-lte/dist/js/jquery.tokeninput.js', ['position' => \yii\web\View::POS_HEAD]);
?>

<div class="sms-queue-form">

   <?php $form = ActiveForm::begin([ 'id' => 'form-compose-sms', 
                                        
                                        'fieldConfig' => ['autoPlaceholder'=>true]
									]); ?>

    <?= $form->field($model, 'to_phone', [
					'addon' => ['prepend' => ['content'=>'To:']]
					])->textInput(['class'=>'facebook-theme']); ?>
                    
     <?php $template_lists = ArrayHelper::map($template, 'id', 'name'); ?>
    <?= $form->field($mod_template, 'id', [
					'addon' => ['prepend' => ['content'=>'Template:']]
					])->dropDownList(
                $template_lists,[
                    'prompt'=>'--- Choose template ---',
                    'onchange'=>'
                        $.get( "'.Url::toRoute('/smsqueue/template').'", { id: $(this).val() } )
                            .done(function( result ) {
                               var data = jQuery.parseJSON(result);
                               
                                $( "#'.Html::getInputId($model, 'message_text').'" ).val( data.body );
                            }
                        );
                    '    
                ]) ->label('Sms-Template')  ?>
   
    <?= $form->field($model, 'message_text')->textarea(['rows'=>'5','maxlength' => 140]) ?>


     <div class="modal-footer clearfix">

            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Discard</button>

            <?= Html::submitButton($model->isNewRecord ? ' <span class="fa fa-envelope"></span> Send Message' : ' <span class="glyphicon glyphicon-floppy-disk"></span> Update', ['class' => $model->isNewRecord ? 'btn btn-success pull-left' : 'btn btn-primary pull-left']) ?> 
        </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">

            $(document).ready(function() {
                
                $(".facebook-theme").tokenInput("<?=Url::to(["emailqueue/emaillist"])?>", 
                {
                    theme: "facebook",
                    propertyToSearch: "value",
                    resultsFormatter: function(item) { return "<li><p>" + item.value + "</p></li>" },
                    tokenFormatter: function(item) { return "<li><p>" + item.value + "</p></li>" },
                    hintText:"Type 'cla' to get classes for sms or Type name",
                    
                });
             });
</script>
