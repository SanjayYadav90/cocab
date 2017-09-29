<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DeliveryAnalytics */

$this->title = 'Update Delivery Analytics: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Delivery Analytics', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="delivery-analytics-update">

    <?= $this->render('_form', [
        'model' => $model,
		//'status' => $status,
		'd_boys' =>$d_boys
    ]) ?>

</div>
