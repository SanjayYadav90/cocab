<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use common\models\CfgList;


$this->title = 'View SMS Template';

?>

<div class="sms-template-view ">

    <?= DetailView::widget([
        'model' => $model,
		'condensed' => true,
		'hAlign' => DetailView::ALIGN_LEFT,
        'enableEditMode' => false,
		'hover' => true,
		'mode' => DetailView::MODE_VIEW,
	/*	'panel' =>[
		'heading'=>"Class: " . $model->name,
		'type'=>DetailView::TYPE_PRIMARY,
		],*/
        'attributes' => [
            //'id',
            'name',
            [
				'attribute'=>'body',	
				'format' =>	'html',	
							
			],
            [
				'attribute'=>'type',	
				'value' =>	CfgList::getListValue('MESSAGE_TEMPLATE_TYPE',$model->type ),	
							
			],
            [
				'attribute'=>'created_at',	
				'value' =>	date('Y-m-d H:i:s', $model->created_at),	
							
			],
            //'org_id',
        ],
    ]) ?>

 <div class="modal-footer clearfix">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Discard</button>
 </div>
</div>
