<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Student */

$this->title = 'Change Delivery Boy';

?>
<div class="assign-student-Route ">

    <?= $this->render('_ChangeBoy_form', [
		'd_boys' => $d_boys,
		'model' => $model,
    ]) ?>

</div>
