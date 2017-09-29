<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\TrackDeliveryBoy */

$this->title = 'Create Track Delivery Boy';
$this->params['breadcrumbs'][] = ['label' => 'Track Delivery Boys', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="track-delivery-boy-create">

    <?= $this->render('_form', [
        'model' => $model,
		'd_boys' =>$d_boys
    ]) ?>

</div>
