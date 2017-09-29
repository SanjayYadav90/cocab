<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Scheduler */

$this->title = 'Update Scheduler: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Schedulers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scheduler-update">

    <?= $this->render('_form', [
        'model' => $model,
		'template_name' => $template_name,
    ]) ?>

</div>
