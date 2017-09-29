<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductBrandName */

$this->title = 'Update Product Brand Name: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Product Brand Names', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="product-brand-name-update">

    <?= $this->render('_form', [
        'model' => $model,
		'status'=>$status
    ]) ?>

</div>
