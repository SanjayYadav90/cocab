<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Scheduler */

$this->title = 'Create Scheduler';
$this->params['breadcrumbs'][] = ['label' => 'Schedulers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scheduler-create">

    <?= $this->render('_form', [
        'model' => $model,
		'template_cat'=>$template_cat,
    ]) ?>

</div>
