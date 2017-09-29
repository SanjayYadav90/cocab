<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductPrice */

$this->title = 'Update Product Price: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Product Prices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="product-price-update">

    <?= $this->render('_form', [
        'model' => $model,
		'price_status' => $price_status,
		'offer_flag' => $offer_flag,
		'offer_unit' => $offer_unit,
		'products'=>$products
    ]) ?>

</div>
