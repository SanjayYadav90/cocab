<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\DeliverySlotName */

$this->title = 'Create Delivery Slot Name';
$this->params['breadcrumbs'][] = ['label' => 'Delivery Slot Names', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="delivery-slot-name-create">

    <?= $this->render('_form', [
        'model' => $model,
		'status'=>$status
    ]) ?>

</div>
