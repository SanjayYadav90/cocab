<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use kartik\grid\GridView;
use backend\models\Users;
use backend\models\Staff;
use backend\models\Products;
use backend\models\Address;
use backend\models\DefaultSetting;

/* @var $this yii\web\View */
/* @var $model backend\models\DeliverySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="delivery-index row">

<div class="col-sm-1">
</div>
<div class="col-sm-10 row">
<div class="col-sm-5">
<h5 style="text-align:right">
       Tomorrow Order Quantity:
    </h5>
</div>
<div class="col-sm-5">
 <h5> <?=isset($model->quantity) ?$model->quantity :"Order not loaded."?> </h5>
</div>

</div>
<div class="col-sm-1">
</div>
   
</div>

