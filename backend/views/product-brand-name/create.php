<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ProductBrandName */

$this->title = 'Create Product Brand Name';
$this->params['breadcrumbs'][] = ['label' => 'Product Brand Names', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-brand-name-create">

    <?= $this->render('_form', [
        'model' => $model,
		'status'=>$status
    ]) ?>

</div>
