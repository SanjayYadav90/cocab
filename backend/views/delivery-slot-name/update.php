<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DeliverySlotName */

$this->title = 'Update Delivery Slot Name: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Delivery Slot Names', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="delivery-slot-name-update">

    <?= $this->render('_form', [
        'model' => $model,
		'status'=>$status
    ]) ?>

</div>
