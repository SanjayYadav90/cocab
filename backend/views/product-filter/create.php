<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ProductFilter */

$this->title = 'Create Product Filter';
$this->params['breadcrumbs'][] = ['label' => 'Product Filters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-filter-create">

    <?= $this->render('_form', [
        'model' => $model,
		'status' => $status
    ]) ?>

</div>
