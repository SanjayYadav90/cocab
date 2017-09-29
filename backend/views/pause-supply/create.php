<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PauseSupply */

$this->title = 'Create Pause Supply';
$this->params['breadcrumbs'][] = ['label' => 'Pause Supplies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pause-supply-create">

    <h1><?php //echo Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
