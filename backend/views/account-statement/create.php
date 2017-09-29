<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\AccountStatement */

$this->title = 'Create Account Statement';
$this->params['breadcrumbs'][] = ['label' => 'Account Statements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-statement-create">

    <?= $this->render('_form', [
        'model' => $model,
		'staff' => $staff,
		'd_boys' =>$d_boys,
    ]) ?>

</div>
