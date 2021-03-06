<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modeltest\SmsQueue */

$this->title = 'Update Sms Queue: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sms Queues', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sms-queue-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
