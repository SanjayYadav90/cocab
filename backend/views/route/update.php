<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Route */

$this->title = 'Update Route: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Routes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="route-update">

    <?= $this->render('_form', [
        'model' => $model,
		'd_boys' => $d_boys,
		'status' => $status,
    ]) ?>

</div>
