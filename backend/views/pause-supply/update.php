<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\PauseSupply */

$this->title = 'Update Pause Supply: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pause Supplies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pause-supply-update">

    <h1><?php //echo Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
