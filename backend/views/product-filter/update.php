<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductFilter */

$this->title = 'Update Product Filter: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Product Filters', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="product-filter-update">

    <?= $this->render('_form', [
        'model' => $model,
		'status' => $status
    ]) ?>

</div>
