<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Delivery */

$this->title = 'Update Delivery: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Deliveries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="delivery-update">

    <?= $this->render('_form', [
        'model' => $model,
		'delivery_status' => $delivery_status,
		'users' =>$users,
		'd_boys' => $d_boys,
		'products' =>$products
    ]) ?>

</div>
