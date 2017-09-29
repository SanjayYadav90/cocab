<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\DeliveryAnalytics */

$this->title = 'Create Delivery Analytics';
$this->params['breadcrumbs'][] = ['label' => 'Delivery Analytics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="delivery-analytics-create">

    <?= $this->render('_form', [
        'model' => $model,
		//'status' => $status,
		'd_boys' =>$d_boys
    ]) ?>

</div>
