<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\TrackHistory */

$this->title = 'Update Track History: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Track History', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="track-history-update">

    <?= $this->render('_form', [
        'model' => $model,
		'd_boys' =>$d_boys
    ]) ?>

</div>
