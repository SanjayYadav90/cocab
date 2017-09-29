<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\CowMilkBilling */

$this->title = 'Create Cow Milk Billing';
$this->params['breadcrumbs'][] = ['label' => 'Cow Milk Billings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cow-milk-billing-create">

    <?= $this->render('_form', [
        'model' => $model,
		'users' =>$users,
    ]) ?>

</div>
