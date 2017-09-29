<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductPrice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-price-form row">
<div class="col-sm-1"></div>
<div class="col-sm-10">
   <?php $form = ActiveForm::begin([ 'id' => 'product-price-form-horizontal',
										'type' => ActiveForm::TYPE_HORIZONTAL,
										'formConfig' => ['labelSpan' => 2, 
										'deviceSize' => ActiveForm::SIZE_LARGE]
									]); ?>


    <?= $form->field($model, 'product_id')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($products,'id', 'name'),
			'options' => ['placeholder' => 'Select a product name ...'],
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

   <?= $form->field($model, 'quantity', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-pushpin"></i>']]])->textInput() ?>


    <?= $form->field($model, 'unit', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-pushpin"></i>']]])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mrp', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-pushpin"></i>']]])->textInput() ?>

    <?php $model->offer_flag = $model->isNewRecord ? 0 : $model->offer_flag; ?>
    <?= $form->field($model, 'offer_flag')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($offer_flag,'value', 'name'),
			'options' => ['placeholder' => 'Select Offer Status ...'],
			'pluginOptions' => [
				'allowClear' => true,
				'onChange' => 'offerstatus',
				
			],
			'size' => Select2::MEDIUM,
			'addon' => [
				'prepend' => [
					'content' => '<i class="glyphicon glyphicon-gift"></i>'
				],
			],
		]); ?>

	<div id="offer">	
	<?php $model->offer_unit = $model->isNewRecord ? 0 : $model->offer_unit; ?>
    <?= $form->field($model, 'offer_unit')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($offer_unit,'value', 'name'),
			'options' => ['placeholder' => 'Select Offer Unit ...'],
			'pluginOptions' => [
				'allowClear' => true,
				
			],
			'size' => Select2::MEDIUM,
			'addon' => [
				'prepend' => [
					'content' => '<i class="glyphicon glyphicon-gift"></i>'
				],
			],
		]); ?>
	
	<?= $form->field($model, 'offer_price', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-pushpin"></i>']]])->textInput(['id'=>'discount-price']) ?>
	
	<?= $form->field($model, 'discounted_mrp', [
						'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-pushpin"></i>']]])->textInput(['readonly' => true, 'id'=>'discount']) ?>

	</div>
	<?php $model->status = $model->isNewRecord ? 1 : $model->status; ?>
   <?= $form->field($model, 'status')->widget(Select2::classname(), [
			'data' => ArrayHelper::map($price_status,'value', 'name'),
			'options' => ['placeholder' => 'Select Status ...'],
			'pluginOptions' => [
				'allowClear' => true,
				
			],
			'size' => Select2::MEDIUM,
			'addon' => [
				'prepend' => [
					'content' => '<i class="glyphicon glyphicon-gift"></i>'
				],
			],
		]); ?>

	
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
	var x = document.getElementById("discount-price");
	//x.addEventListener("keyup", myFocusFunction, true);
	var y = document.getElementById("productprice-mrp");
	x.addEventListener("blur", myBlurFunction, true);
	y.addEventListener("blur", myBlurFunction, true);
	function myFocusFunction() {
		var myselect = document.getElementById("productprice-offer_unit");
		alert(myselect.options[myselect.selectedIndex].value);
		var text_value = document.getElementById("discount-price").value;
		document.getElementById("discount").value = text_value;
	}

	 function myBlurFunction() {
		var myselect = document.getElementById("productprice-offer_unit");
		var offerStyle = myselect.options[myselect.selectedIndex].value;
		var discount_price = document.getElementById("discount-price").value;
	    var mrp = document.getElementById("productprice-mrp").value;
		var text_value = '';
		if(offerStyle == 1)   //offer in %
		{
			text_value =  mrp -(mrp * discount_price/100);
		}
		else   //offer in fixed value
		{          
			text_value =  mrp - discount_price;
		}
		
		document.getElementById("discount").value = text_value;
	} 
	
	 $(document).ready(function(){
		$('#productprice-offer_flag').on('change', function() {
		  if(this.value == 1)
		  {
			  $('#offer').show();
			   myBlurFunction();
		  }
		  else
		  {
			  $('#offer').hide();
		  }
		});
		$('#productprice-offer_unit').on('change', function() {
			   myBlurFunction();
		});
		
		var flag = document.getElementById("productprice-offer_flag");
		var offerStatus = flag.options[flag.selectedIndex].value;
		if(offerStatus == 1)
		{
			$('#offer').show();
		}
		else
		  {
			  $('#offer').hide();
		  }
  }); 
  

</script>
<style>
#offer{display:none;}
</style>

