<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Products */

$this->title = 'Create Products';
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-create">

    <h1><?php //echo Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		'product_status'=> $product_status,
		'cat_list' => $cat_list,
		'prod_filter'=>$prod_filter,
		'slots'=>$slots,
		'brands'=>$brands
		
    ]) ?>

</div>
