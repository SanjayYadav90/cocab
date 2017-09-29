<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Staff */

$this->title = 'Update User: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'User', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="staff-update">

    <h1><?php //echo Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		'mod_users' => $mod_users,
		'mod_address' => $mod_address,
		'country' => $country,
		'state' => $state,
		'role' => $role,
		'delivery_boys' => $delivery_boys,
		'distributors' => $distributors,
		'route' => $route,
    ]) ?>

</div>
