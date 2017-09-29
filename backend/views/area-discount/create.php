<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\AreaDiscount */

$this->title = 'Create Area Discount';
$this->params['breadcrumbs'][] = ['label' => 'Area Discount', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="area-discount-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
