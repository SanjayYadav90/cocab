<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\CowMilkBilling */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cow Milk Billings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cow-milk-billing-view">

    <p>
        <?php // echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php /* echo Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) */?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'subscription_id',
            'user_id',
			[
			 'attribute'=>'bill_cycle',
			 'format'=>['DateTime','php:M-Y']
			],
            'delivered_quantity',
            'sub_total',
            'referral_discount',
            'voucher_discount',
            'tax',
            'bill_amount',
            'previous_due_amount',
            'net_payable_amount',
            'billing_gen_date',
            'created_by',
            'updated_by',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
