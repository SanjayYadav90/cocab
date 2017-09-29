<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\RouteMap */

$this->title = 'Create Route Map';
$this->params['breadcrumbs'][] = ['label' => 'Route Maps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="route-map-create">

    <?= $this->render('_form', [
        'model' => $model,
		'users' => $users,
		'status' => $status,
		'route' => $route,
    ]) ?>

</div>
