<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SmsTemplate */

$this->title = 'Update Sms Template: ' . ' ' . $model->name;

?>
<div class="sms-template-update">

    <?= $this->render('_form', [
        'model' => $model,
		'message_template'=>$message_template,
		'template_cat'=>$template_cat
    ]) ?>

</div>
