<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\TrackHistory */

$this->title = 'Create Track History';
$this->params['breadcrumbs'][] = ['label' => 'Track History', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="track-history-create">

    <?= $this->render('_form', [
        'model' => $model,
		'd_boys' =>$d_boys
    ]) ?>

</div>
