<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\AccountStatement */

$this->title = 'Update Account Statement: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Account Statements', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="account-statement-update">

    <?= $this->render('_form', [
        'model' => $model,
		'staff' =>$staff,
		'd_boys' =>$d_boys,
    ]) ?>

</div>
