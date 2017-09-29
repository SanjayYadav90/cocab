<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use common\models\User;
use backend\models\ContactUs;

/**
 * User Controller API

 */
class ContactusController extends ActiveController
{
    public $modelClass = 'backend\models\ContactUs';    
	
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
	
	
	
	public function actionAddContact()
	{
		if(!isset($_POST['user_id']))
		{
            return [
				"status" => "000001",
				"msg" => "user id not set" 
			];
		}
		$user_id = $_POST['user_id'];
		$result = [];
		$out = [];
        $new_list= [];
        
		$product = Products::find()->asArray()->All();
		if(isset($product) && ($product != null))
		{
			foreach ($product as $row) 
			{
				

				$subscription = Subscription::find()->where(['user_id' => $user_id,'product_id' => $row['id']])->asArray()->One();
				if(isset($subscription) && ($subscription != null))
				{
						$out['subscription'] =	'yes';
						//$out['subscription_id'] =	$subscription['id'];
				}
				else
				{
					$out['subscription'] =	'no';
					//$out['subscription_id'] = null;
				}
				$out['product_id'] = $row['id'];  
				$out['product_name'] = $row['name'];
				$out['product_description'] = $row['description'];
				$out['product_price'] = $row['price'];
				$out['product_image_url'] = !empty($row['image']) ? $row['image'] : Yii::$app->request->baseUrl .'/theme/dist/img/no_image.png';
				array_push($new_list,$out);
			}		
		}

		$result["status"] = "000000";
		$result["msg"] = "Success";
		$result["product_list"] = isset($new_list) ? $new_list : null;
		
		return $result;	
	}

}
