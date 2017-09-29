<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\XrefPauseSubscription */

$this->title = 'Create Edit/Pause Subscription';
$this->params['breadcrumbs'][] = ['label' => 'Edit/Pause Subscriptions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="xref-pause-subscription-create">

    <?= $this->render('_form', [
        'model' => $model,
		'users' =>$users,
		'products' =>$products
    ]) ?>

</div>
