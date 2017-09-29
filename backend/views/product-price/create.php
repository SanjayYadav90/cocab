<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ProductPrice */

$this->title = 'Create Product Price';
$this->params['breadcrumbs'][] = ['label' => 'Product Prices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-price-create">

    <?= $this->render('_form', [
        'model' => $model,
		'price_status' => $price_status,
		'offer_flag' => $offer_flag,
		'offer_unit' => $offer_unit,
		'products'=>$products
    ]) ?>

</div>
