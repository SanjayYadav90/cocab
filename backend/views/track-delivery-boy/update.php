<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\TrackDeliveryBoy */

$this->title = 'Update Track Delivery Boy: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Track Delivery Boys', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="track-delivery-boy-update">

    <?= $this->render('_form', [
        'model' => $model,
		'd_boys' =>$d_boys
    ]) ?>

</div>
