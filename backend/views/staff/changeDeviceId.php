<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Student */

$this->title = 'Change Device Id';

?>
<div class="assign-student-Route ">

    <?= $this->render('_ChangeDeviceId_form', [
		'users' => $users,
		'model' => $model,
    ]) ?>

</div>
