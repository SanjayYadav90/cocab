<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PaytmTransaction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="paytm-transaction-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'txn_response')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'verify_response')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
