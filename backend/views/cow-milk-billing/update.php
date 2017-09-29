<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\CowMilkBilling */

$this->title = 'Update Cow Milk Billing: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cow Milk Billings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cow-milk-billing-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
