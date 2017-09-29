<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use common\models\User;
use backend\models\Products;
//use backend\models\Subscription;
use backend\models\DefaultSetting;
use backend\models\Category;


/**
 * User Controller API

 */
class CategoryController extends ActiveController
{
    public $modelClass = 'backend\models\Category';    
	
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
	
	
	
	public function actionCategoryList()
	{
		$result = [];
		$out = [];
        $new_list= [];
        
		$category = Category::find()->where(['<>','status', 0])->orderBy(['name' => SORT_ASC])->asArray()->All();
		if(isset($category) && ($category != null))
		{
			foreach ($category as $row) 
			{
				$out['cat_id'] = $row['id'];			
				$out['cat_name'] = $row['name'];
				$out['cat_description'] = $row['description'];
				$out['cat_image_url'] = !empty($row['image']) ? $row['image'] : Yii::$app->request->baseUrl .'/theme/dist/img/no_image.png';			
				$out['cat_status'] = $row['status'];
				array_push($new_list,$out);
			}		
		}

		$result["status"] = "000000";
		$result["msg"] = "Success";
		$result["cat_list"] = isset($new_list) ? $new_list : 'No Category found';
		
		return $result;	
	}
	public function actionCategoryName()
	{
		if(!isset($_POST['cat_id']))
		{
            return [
				"status" => "000001",
				"msg" => "category id not set" 
			];
		} 
		
		$result = [];
		$out = [];
        $cat_id = $_POST['cat_id'];
		$category = Category::find()->where(['id'=> $cat_id])->asArray()->One();
		if(isset($category) && ($category != null))
		{
			$out['cat_id'] = $category['id'];			
			$out['cat_name'] = $category['name'];
			$out['cat_description'] = $category['description'];
			$out['cat_image_url'] = !empty($row['image']) ? $category['image'] : Yii::$app->request->baseUrl .'/theme/dist/img/no_image.png';			
			$out['cat_status'] = $category['status'];
			$result["status"] = "000000";
			$result["msg"] = "Success";
			$result["cat_list"] = $out ;
		}
		else{
			$result["status"] = "000001";
			$result["msg"] = "No Category found";
		}

		return $result;	
	}

}
