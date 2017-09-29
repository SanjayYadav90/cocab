<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PaytmTransaction */

$this->title = 'Create Paytm Transaction';
$this->params['breadcrumbs'][] = ['label' => 'Paytm Transactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="paytm-transaction-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
