<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\XrefPauseSubscription */

$this->title = 'Update Edit/Pause Subscription: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Edit/Pause Subscriptions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="xref-pause-subscription-update">

    <?= $this->render('_form', [
        'model' => $model,
		'users' =>$users,
		'products' =>$products
    ]) ?>

</div>
