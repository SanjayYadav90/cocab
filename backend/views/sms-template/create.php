<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\SmsTemplate */

$this->title = 'Create Sms Template';
$this->params['breadcrumbs'][] = ['label' => 'Sms Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sms-template-create">

    <?= $this->render('_form', [
        'model' => $model,
		'message_template'=>$message_template,
		'template_cat'=>$template_cat
    ]) ?>

</div>
