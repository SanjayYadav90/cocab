<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\AreaDiscount */

$this->title = 'Update Area Discount: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Area Discount ', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="area-discount-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
