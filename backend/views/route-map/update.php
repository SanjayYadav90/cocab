<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\RouteMap */

$this->title = 'Update Route Map: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Route Maps', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="route-map-update">

    <?= $this->render('_form', [
        'model' => $model,
		'users' => $users,
		'status' => $status,
		'route' => $route,
    ]) ?>

</div>
