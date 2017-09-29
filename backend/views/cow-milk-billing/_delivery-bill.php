<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use backend\models\Users;
use backend\models\Staff;
use backend\models\Products;
use backend\models\Address;
use backend\models\DefaultSetting;

/* @var $this yii\web\View */
/* @var $model backend\models\DeliverySearch */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Cow Milk Billing Details';
$this->params['breadcrumbs'][] = ['label' => 'Cow Milk Billings', 'url' => ['index']];

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="delivery-index">
  <div class="kv-detail-content">
	<div class="row">
        <div class="col-sm-1">
        </div>
		 <div class="col-sm-10">
		  <div class="col22-sm-5" style="float:left">
           <p > <b>Bill No. 		:  </b><?=$model->id?> </p>
		   <p > <b>Bill Cycle 	:  </b><?=date("M-Y" ,strtotime($model->bill_cycle));?>  (<?=date("d-M-Y" ,strtotime($model->start_date))?> - <?=date("d-M-Y" ,strtotime($model->end_date))?> )</b></p>
		   <p> <b>Dated 	: </b><?=date("d-M-Y H:i:s" ,strtotime($model->billing_gen_date));?> </p>
		  </div>
       
		 <div class="col22-sm-5" style="float:right; text-align:justify">
			<p><b> Customer Name: </b><?=$model->userName?></p>
			<p><b> Customer Mobile: </b><?=$model->user->username?></p>
			<p><b> Address: </b><?=$model->user->staff->displayAddress?></p>
            
        </div>
		 </div>
		<div class="col-sm-1">
        </div>
	</div>
    <div class="row">
        <div class="col-sm-1">
        </div>
        <div class="col-sm-10">
            <table class="table table-bordered table-condensed table-hover small kv-table">
                <tbody>
				<tr class="success">
                    <th colspan="6" class="text-center text-danger">Bill Amount Breakup</th>
                </tr>
                <tr class="active">
                    <th class="text-center">#</th>
                    <th>Date</th>
					<th>Product Name</th>
					<th class="text-right">Unit Price(Rs)</th>
					<th class="text-right">Qty.</th>
                    <th class="text-right">Amt.(Rs)</th>
                </tr>
				<?php 
				
				if(isset($delivery_item) && ($delivery_item != null))
				{
					$i = 1;
					//$delivery_item = $delivery_item[$i];
					foreach ($delivery_item as $drow) 
					{ 
				?>
					<tr>
						<td class="text-center"><?=$i?></td><td><?=date("d-M-Y" ,strtotime($drow['delivery_date']))?></td><td><?=$drow['product']?></td><td class="text-right"><?=$drow['mrp'] - $drow['area_discount'].".00"?></td><td class="text-right"><?=$drow['delivered'].' ltr'?></td><td class="text-right"><?=$drow['row_amount'].'.00';?> </td>
					</tr>
               <?php 
					$i++;
					}
				}
				else{ ?>
					<tr>
						<td class="text-center">1</td><td></td><td></td><td></td><td> No Delivery breakup found</td><td class="text-right"></td>
					</tr>
				<?php 
				}
			   ?>
                <tr class="">
                    <th></th><th></th><th></th><th class="text-right">Total</th><th class="text-right"><?=$model->delivered_quantity.' ltr';?></th><th class="text-right"><?=$model->sub_total.'.00';?></th>
                </tr>
				<?php if($model->referral_discount != 0) { ?>
				<tr class="">
                    <th></th><th></th><th></th><th class="text-right">Referral Discount</th><th></th><th class="text-right">-<?=$model->referral_discount.'.00';?></th>
                </tr>
				<?php } ?>
				<?php if($model->voucher_discount != 0) { ?>
				<tr class="">
                    <th></th><th></th><th></th><th class="text-right">Voucher Discount</th><th></th><th class="text-right">-<?=$model->voucher_discount.'.00';?></th>
                </tr>
				<?php } ?>
				<?php if($model->tax != 0) { ?>
				<tr class="">
                    <th></th><th></th><th></th><th class="text-right">Taxes</th><th></th><th class="text-right">+<?=$model->tax.'.00';?></th>
                </tr>
				<?php } ?>
				<?php if($model->previous_due_amount != 0) { ?>
				<tr class="">
                    <th></th><th></th><th></th><th class="text-right">Previous Due Amount</th><th></th><th class="text-right"><?=$model->previous_due_amount.'.00';?></th>
                </tr>
				<?php } ?>
				 <tr class="">
                    <th></th><th></th><th></th><th class="text-right">Total Bill Amount</th><th></th><th class="text-right"><?=$model->net_payable_amount.'.00';?></th>
                </tr>
            </tbody></table>
        </div>
        <div class="col-sm-1">
            
        </div>
       
	</div>
   </div>
   
</div>

