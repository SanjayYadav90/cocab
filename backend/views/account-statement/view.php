<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\AccountStatement */

$this->title = $model->userName;
$this->params['breadcrumbs'][] = ['label' => 'Account Statements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-statement-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'transaction_date',
            'amount',
            'payment_mode',
			'payment_status',
			'type',
			'bank_name',
			'bank_branch',
			'cheque_number',
            [
				'attribute'=>'user_id',	
				'value' =>	 $model->userName,	
							
			],
			[
				'attribute'=>'delivery_boy_id',	
				'value' =>	 $model->deliveryBoyName,	
							
			],
			'type',
           [
				'attribute'=>'created_at',	
				'value' =>	date('Y-m-d H:i:s', $model->created_at),	
							
			],
            [
				'attribute'=>'updated_at',	
				'value' =>	date('Y-m-d H:i:s', $model->updated_at),	
							
			],
        ],
    ]) ?>

</div>
