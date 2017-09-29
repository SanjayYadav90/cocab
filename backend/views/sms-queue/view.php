<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use common\models\CfgList;

/* @var $this yii\web\View */
/* @var $model frontend\models\SmsQueue */

$this->title = 'Sms Queues';

?>

<div class="sms-queue-view">
	
    <?= DetailView::widget([
        'model' => $model,
		'condensed' => true,
		'hAlign' => DetailView::ALIGN_LEFT,
		'hover' => true,
		'mode' => DetailView::MODE_VIEW,
		'enableEditMode' => false,
		 /* 'panel' =>[
			//'heading'=>"SMS: " . $model->to_phone,
			'type'=>DetailView::TYPE_PRIMARY,
			],  */
        'attributes' => [
			'to_phone',
            'message_text:html',
            [
				'attribute' => 'status',
				'value' => CfgList::getListValue("MESSAGE_STATUS",$model->status),	
			],
			[
				'attribute' => 'type',
				'value' => CfgList::getListValue("MESSAGE_TYPE",$model->type),	
			],
			'attempts',
            'last_attempt',
            'date_sent',
			[
				'attribute' =>'user_id',
				'format'=>'raw',
				'value'=>$model->user->username,
			],
			[
				'attribute'=>'created_at',
				'value' => date('Y-m-d H:i:s', $model->created_at),			
			],
        ],
    ]) ?>

	<div class="modal-footer clearfix">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Discard</button>
 </div>
</div>
