<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\SmsQueue */

$this->title = 'Compose Sms';
$this->params['breadcrumbs'][] = ['label' => 'Sms Queues', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sms-queue-create">

    <?= $this->render('_form', [
        'model' => $model,
        'template'=>$template,
        'mod_template'=>$mod_template,
    ]) ?>

</div>
