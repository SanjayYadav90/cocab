<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Student */

$this->title = 'Change Delivery Status';

?>
<div class="assign-student-Route ">

    <?= $this->render('_ChangeStatus_form', [
		'delivery_status' => $delivery_status,
		'model' => $model,
    ]) ?>

</div>
