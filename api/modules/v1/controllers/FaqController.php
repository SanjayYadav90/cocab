<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use common\models\User;
use backend\models\Faq;
/* use backend\models\Subscription;
use backend\models\PauseSupply; */

/**
 * User Controller API

 */
class FaqController extends ActiveController
{
    public $modelClass = 'backend\models\Faq';    
	
	public function behaviors()
	{
		$behaviors = parent::behaviors();
		 /* $behaviors['authenticator'] = [
			'class' => HttpBasicAuth::className(),
		];  */
		$behaviors['bootstrap'] = [
            'class' => ContentNegotiator::className(),
			'formats' => [
				'application/json' => Response::FORMAT_JSON,
			],
		];  
		return $behaviors;
	}
	
	
	
	public function actionFaqList()
	{
		$result = [];
		$out = [];
        $new_list= [];
        
		$faqs = Faq::find()->asArray()->All();
		if(isset($faqs) && ($faqs != null))
		{
			foreach ($faqs as $row) 
			{
				$out['id'] = $row['id'];  
				$out['question'] = $row['question'];
				$out['answer'] = $row['answer'];
				$out['type'] = $row['type'];
				array_push($new_list,$out);
			}		
		}

		$result["status"] = "000000";
		$result["msg"] = "Success";
		$result["faq_list"] = isset($new_list)&& !empty($new_list) ? $new_list : null;
		
		return $result;	
	}

}
