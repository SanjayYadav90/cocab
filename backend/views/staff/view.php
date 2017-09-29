<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Staff */

$this->title = $model->first_name;
$this->params['breadcrumbs'][] = ['label' => 'User', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="staff-view">

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
            //'id',
            'first_name',
            'last_name',
            'phone',
            'email:email',
			[
				'attribute'=>'profile_pic',
				'format'=>'html',
				'value' =>	 $model->displayImage,	
							
			],
			[
				'attribute'=>'user_id',	
				'value' =>	 $model->staff,	
							
			],
			[
				'attribute'=>'address_id',	
				'value' =>	 $model->displayAddress,	
							
			],
			[
				'attribute'=>'staff_type',	
				'value' =>	 $model->staffType,	
							
			],
            
            [
				'attribute'=>'created_at',	
				'value' =>	date('Y-m-d H:i:s', $model->created_at),	
							
			],
            [
				'attribute'=>'updated_at',	
				'value' =>	date('Y-m-d H:i:s', $model->updated_at),	
							
			],
            //'created_by',
            //'updated_by',
        ],
    ]) ?>

</div>
