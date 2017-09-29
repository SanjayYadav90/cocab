<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use common\models\User;
use backend\models\Products;
use backend\models\Subscription;
use backend\models\DefaultSetting;
use backend\models\Category;
use backend\models\ProductPrice;
use backend\models\ProductFilter;
use backend\models\ProductBrandName;


/**
 * User Controller API

 */
class ProductController extends ActiveController
{
    public $modelClass = 'backend\models\Products';    
	
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
	
	
	
	public function actionProductList()
	{
		if(!isset($_POST['user_id']))
		{
            return [
				"status" => "000001",
				"msg" => "user id not set" 
			];
		}
		$user_id = $_POST['user_id'];
		$cat_id = isset($_POST['cat_id']) ? $_POST['cat_id'] : null;
		$result = [];
		$out = [];
        $new_list= [];
		/* $out['user_id'] =	$_POST['user_id'];
		$out['cat_id'] =	$_POST['cat_id'];
		$result["status"] = "000000";
		$result["msg"] = "Success";
		$result["product_list"] = isset($out) ? $out : null; 
		return $result; */
        if(!isset($cat_id) || empty($cat_id))
		{
			$product = Products::find()->where(['<>','status', 0])->orderBy(['name' => SORT_ASC])->All();
		}
		else
		{
			$product = Products::find()->where(['cat_id' => $cat_id])->andWhere(['<>','status', 0])->orderBy(['name' => SORT_ASC])->All();
		}
			if(isset($product) && ($product != null))
			{
				foreach ($product as $row) 
				{
					

					$subscription = Subscription::find()->where(['user_id' => $user_id,'product_id' => $row->id])->asArray()->One();
					if(isset($subscription) && ($subscription != null))
					{
						if($subscription['status'] == 2)   /// unsettled 
						{
								
								$out['subscription'] =	'unsettled';
								$out['subscription_id'] =	$subscription['id'];
								$out['quantity'] =	$subscription['quantity'];
						}
						elseif($subscription['status'] == 1){   // subscribed
							$out['subscription'] =	'yes';
							$out['subscription_id'] =	$subscription['id'];
							$out['quantity'] =	$subscription['quantity'];
							
						}
						else              // unsubscribed
						{
							$out['subscription'] =	'no';      
							$out['subscription_id'] = null;
							$out['quantity'] = null;
						}
							
					}
					else              // no subscriction
						{
							$out['subscription'] =	'no';      
							$out['subscription_id'] = null;
							$out['quantity'] = null;
						}
					
					
					$product_prices = ProductPrice::find()->where(['product_id' => $row->id,'status' => 1])->All();
					if(isset($product_prices)){
						$price = [];
						$price_list = [];
						foreach ($product_prices as $product_price) 
						{
							$price['quantity'] 	= $product_price->quantity;
							$price['unit'] 		= $product_price->unit;
							$price['mrp'] 		= $product_price->mrp;
							$price['offer_discount'] = $product_price->offer_price;
							$price['offer_style'] = $product_price->offer_unit;
							$price['offer_status'] = $product_price->offer_flag;
							$price['discounted_mrp'] = $product_price->discounted_mrp;
							array_push($price_list,$price);
						}
					} 
					$out['product_id'] = $row->id;			
					$out['product_name'] = $row->name;
					$out['product_description'] = $row->description;
					$out['product_price'] = $row->price;
					$out['brand_name'] = isset($row->brand) ? $row->brand->name : null;
					$out['brand_image'] = isset($row->brand) ? $row->brand->image : null;
					$out['delivery_slot'] = isset($row->deliverySlot) ? $row->name : null;
					$out['product_filter'] = isset($row->product_filter) ?  $row->product_filter : null;
					$out['product_image_url'] = !empty($row->image) ? $row->image : Yii::$app->request->baseUrl .'/admin-lte/dist/img/no_image.png';
					$out['cat_name'] = isset($row->category) ?  $row->category->name : null;	
					$out['cat_id'] = isset($row->category) ?  $row->category->id : null;						
					$out['status'] = $row->status;
					$out['product_price_list'] = $price_list;
					array_push($new_list,$out);
				}		
			}
		
		$result["status"] = "000000";
		$result["msg"] = "Success";
		$result["product_list"] = isset($new_list) ? $new_list : null;
		
		return $result;	
	}
	
	public function actionSearchProduct()
	{
		if(!isset($_POST['search_key']))
		{
            return [
				"status" => "000001",
				"msg" => "must be type product name" 
			];
		}
		
		if(!isset($_POST['user_id']))
		{
            return [
				"status" => "000001",
				"msg" => "user id not set" 
			];
		}
		$user_id = $_POST['user_id'];
		$searchVal = $_POST['search_key'];
		
		$result = [];
		$out = [];
        $new_list= [];
       
			$product = Products::find()->where(['<>','status', 0])->andWhere(['LIKE', 'name', $searchVal])->orderBy(['name' => SORT_ASC])->asArray()->all();
			
			//return $product;
			if(isset($product) && ($product != null))
			{
				foreach ($product as $row) 
				{
					

					$subscription = Subscription::find()->where(['user_id' => $user_id,'product_id' => $row['id'],'status' => 1])->asArray()->One();
					if(isset($subscription) && ($subscription != null))
					{
							$out['subscription'] =	'yes';
							$out['subscription_id'] =	$subscription['id'];
							$out['quantity'] =	$subscription['quantity'];
					}
					else
					{
						$out['subscription'] =	'no';
						$out['subscription_id'] = null;
						$out['quantity'] = null;
					}
					$out['product_id'] = $row['id'];			
					$out['product_name'] = $row['name'];
					$out['product_description'] = $row['description'];
					$out['product_price'] = $row['price'];
					$out['product_image_url'] = !empty($row['image']) ? $row['image'] : Yii::$app->request->baseUrl .'/theme/dist/img/no_image.png';
					$out['cat_id'] = $row['cat_id'];			
					$out['status'] = $row['status'];
					array_push($new_list,$out);
				}		
			}
		
		$result["status"] = "000000";
		$result["msg"] = "Success";
		$result["product_list"] = isset($new_list) ? $new_list : null;
		
		return $result;	
	}
	
	public function actionGetProductList()
	{
		if(!isset($_POST['cat_id']) && !isset($_POST['user_id']))
		{
            return [
				"status" => "000001",
				"msg" => "Category or user name not set" 
			];
		}
		$cat_id = $_POST['cat_id'];
		$user_id = $_POST['user_id'];
		$filter_id = isset($_POST['filter_id']) ? $_POST['filter_id'] : null;
		$result = [];
		$out = [];
        $new_list= [];
		$fillter = []; 
		//$all_filter = [];
        if(!isset($filter_id) || empty($filter_id))
		{
			$product = Products::find()->where(['<>','status', 0])->andWhere(['cat_id' => $cat_id])->orderBy(['name' => SORT_ASC])->All();
			
		}
		else
		{
			$filter_model = ProductFilter::findOne($filter_id);
			$filter = $filter_model->name;
			$product = Products::find()->where(['cat_id' => $cat_id])->andWhere(['<>','status', 0])->andWhere(['like','product_filter',$filter ])->orderBy(['name' => SORT_ASC])->All();
		}
			if(isset($product) && ($product != null))
			{
				foreach ($product as $row) 
				{
					

					$subscription = Subscription::find()->where(['user_id' => $user_id,'product_id' => $row->id,'status' => 1])->asArray()->One();
					if(isset($subscription) && ($subscription != null))
					{
							$out['subscription'] =	'yes';
							$out['subscription_id'] =	$subscription['id'];
							$out['quantity'] =	$subscription['quantity'];
					}
					else
					{
						$out['subscription'] =	'no';
						$out['subscription_id'] = null;
						$out['quantity'] = null;
					}
					
					$product_prices = ProductPrice::find()->where(['product_id' => $row->id,'status' => 1])->All();
					if(isset($product_prices)){
						$price = [];
						$price_list = [];
						foreach ($product_prices as $product_price) 
						{
							$price['price_id'] 	= $product_price->id;
							$price['quantity'] 	= $product_price->quantity;
							$price['unit'] 		= $product_price->unit;
							$price['mrp'] 		= $product_price->mrp;
							$price['offer_discount'] = $product_price->offer_price;
							$price['offer_style'] = $product_price->offer_unit;
							$price['offer_status'] = $product_price->offer_flag;
							$price['discounted_mrp'] = $product_price->discounted_mrp;
							array_push($price_list,$price);
						}
					} 
					
					$all_filter[] = $row->product_filter;
					$out['product_id'] = $row->id;			
					$out['product_name'] = $row->name;
					$out['product_description'] = $row->description;
					$out['product_price'] = $row->price;
					$out['brand_name'] = isset($row->brand) ? $row->brand->name : null;
					$out['brand_image'] = isset($row->brand) ? $row->brand->image : null;
					$out['delivery_slot'] = isset($row->deliverySlot) ? $row->name : null;
					$out['product_filter'] = isset($row->product_filter) ?  $row->product_filter : null;
					$out['product_image_url'] = !empty($row->image) ? $row->image : Yii::$app->request->baseUrl .'/admin-lte/dist/img/no_image.png';
					$out['cat_name'] = isset($row->category) ?  $row->category->name : null;	
					$out['cat_id'] = isset($row->category) ?  $row->category->id : null;						
					$out['status'] = $row->status;
					$out['product_price_list'] = $price_list;
					array_push($new_list,$out);
				}

					if(!empty($all_filter)){
					
						$fill = [];
						$all_filter = implode(",",$all_filter);
						$all_filter = explode(",",$all_filter);
						$all_filter = implode(",",$all_filter);
						$all_filter = explode(", ",$all_filter);
						$all_filter = implode(",",$all_filter);
						$all_filter = explode(",",$all_filter);
					    $all_filter =  array_unique($all_filter);
						$all_filter = array_filter($all_filter);
						foreach($all_filter as $filter)
						{
							$filter_model = ProductFilter::find()->where(['name'=>$filter])->one();
							$fill['id'] = $filter_model->id;
							$fill['name'] = $filter_model->name;
							array_push($fillter,$fill);
						}
						//$main_fillter['filter_list'] = $fillter;
						//array_push($new_list,$main_fillter);
					}
			}
		
		$result["status"] = "000000";
		$result["msg"] = "Success";
		$result["product_list"] = isset($new_list) ? $new_list : null;
		$result["filter_list"] = isset($fillter) ? $fillter : null;
		
		return $result;	
	}
	
	public function actionGetProductSort()
	{
		if(!isset($_POST['cat_id']) && !isset($_POST['user_id']))
		{
            return [
				"status" => "000001",
				"msg" => "Category or user name not set" 
			];
		}
		$cat_id = $_POST['cat_id'];
		$user_id = $_POST['user_id'];
		$sort_id = isset($_POST['sort_id']) ? $_POST['sort_id'] : null;
		$result = [];
		$out = [];
        $new_list= [];
		$fillter = [];
		//$all_filter = [];
        
			//$filter_model = ProductFilter::findOne($filter_id);
			//$filter = $filter_model->name;
			switch($sort_id){
				Case '1' :
				 $product = Products::find()->where(['cat_id' => $cat_id])->andWhere(['<>','status', 0])->orderBy(['name' => SORT_ASC])->All();
				 break;
				 Case '2' :
				 $product = Products::find()->where(['cat_id' => $cat_id])->andWhere(['<>','status', 0])->orderBy(['name' => SORT_DESC])->All();
				 break;
				 Case '3' :
				 $product = Products::find()->leftJoin('product_price', '`product_price`.`product_id` = `products`.`id`')->where(['products.cat_id' => $cat_id])->andWhere(['<>','products.status', 0])->orderBy(['(product_price.mrp - product_price.discounted_mrp)' => SORT_ASC])->All();
				 break;
				 Case '4' :
				 $product = Products::find()->leftJoin('product_price', '`product_price`.`product_id` = `products`.`id`')->where(['products.cat_id' => $cat_id])->andWhere(['<>','products.status', 0])->orderBy(['(product_price.mrp - product_price.discounted_mrp)' => SORT_DESC])->All();
				 break;
				 Case '5' :
				 $product = Products::find()->leftJoin('product_price', '`product_price`.`product_id` = `products`.`id`')->where(['products.cat_id' => $cat_id])->andWhere(['<>','products.status', 0])->orderBy(['product_price.mrp' => SORT_ASC])->All();
				 break;
				 Case '6' :
				 $product = Products::find()->leftJoin('product_price', '`product_price`.`product_id` = `products`.`id`')->where(['products.cat_id' => $cat_id])->andWhere(['<>','products.status', 0])->orderBy(['product_price.mrp ' => SORT_DESC])->All();
				 break;
				 default :
				 $product = Products::find()->where(['cat_id' => $cat_id])->andWhere(['<>','status', 0])->orderBy(['popularity' => SORT_ASC])->All();
				 
			}
			//$product = Products::find()->where(['cat_id' => $cat_id])->andWhere(['<>','status', 0])->andWhere(['like','product_filter',$filter ])->orderBy(['name' => SORT_ASC])->All();
			if(isset($product) && ($product != null))
			{
				//return $product;
				foreach ($product as $row) 
				{
					

					$subscription = Subscription::find()->where(['user_id' => $user_id,'product_id' => $row->id,'status' => 1])->asArray()->One();
					if(isset($subscription) && ($subscription != null))
					{
							$out['subscription'] =	'yes';
							$out['subscription_id'] =	$subscription['id'];
							$out['quantity'] =	$subscription['quantity'];
					}
					else
					{
						$out['subscription'] =	'no';
						$out['subscription_id'] = null;
						$out['quantity'] = null;
					}
					
					switch($sort_id){
						Case '1' :
						 $product_prices = ProductPrice::find()->where(['product_id' => $row->id,'status' => 1])->orderBy(['mrp' => SORT_ASC])->All();
						 break;
						 Case '2' :
						 $product_prices = ProductPrice::find()->where(['product_id' => $row->id,'status' => 1])->orderBy(['mrp' => SORT_DESC])->All();
						 break;
						 Case '3' :
						 $product_prices = ProductPrice::find()->where(['product_id' => $row->id,'status' => 1])->orderBy(['(mrp - discounted_mrp)' => SORT_ASC])->All();
						 break;
						 Case '4' :
						 $product_prices = ProductPrice::find()->where(['product_id' => $row->id,'status' => 1])->orderBy(['(mrp - discounted_mrp)' => SORT_DESC])->All();
						 break;
						 Case '5' :
						 $product_prices = ProductPrice::find()->where(['product_id' => $row->id,'status' => 1])->orderBy(['mrp' => SORT_ASC])->All();
						 break;
						 Case '6' :
						 $product_prices = ProductPrice::find()->where(['product_id' => $row->id,'status' => 1])->orderBy(['mrp ' => SORT_DESC])->All();
						 break;
						 default :
						 $product_prices = ProductPrice::find()->where(['product_id' => $row->id,'status' => 1])->orderBy(['id' => SORT_ASC])->All();
						 
					}
					//$product_prices = ProductPrice::find()->where(['product_id' => $row->id,'status' => 1])->All();
					if(isset($product_prices)){
						$price = [];
						$price_list = [];
						foreach ($product_prices as $product_price) 
						{
							$price['price_id'] 	= $product_price->id;
							$price['quantity'] 	= $product_price->quantity;
							$price['unit'] 		= $product_price->unit;
							$price['mrp'] 		= $product_price->mrp;
							$price['offer_discount'] = $product_price->offer_price;
							$price['offer_style'] = $product_price->offer_unit;
							$price['offer_status'] = $product_price->offer_flag;
							$price['discounted_mrp'] = $product_price->discounted_mrp;
							array_push($price_list,$price);
						}
					} 
					
					$all_filter[] = $row->product_filter;
					$out['product_id'] = $row->id;			
					$out['product_name'] = $row->name;
					$out['product_description'] = $row->description;
					$out['product_price'] = $row->price;
					$out['brand_name'] = isset($row->brand) ? $row->brand->name : null;
					$out['brand_image'] = isset($row->brand) ? $row->brand->image : null;
					$out['delivery_slot'] = isset($row->deliverySlot) ? $row->name : null;
					$out['product_filter'] = isset($row->product_filter) ?  $row->product_filter : null;
					$out['product_image_url'] = !empty($row->image) ? $row->image : Yii::$app->request->baseUrl .'/admin-lte/dist/img/no_image.png';
					$out['cat_name'] = isset($row->category) ?  $row->category->name : null;	
					$out['cat_id'] = isset($row->category) ?  $row->category->id : null;						
					$out['status'] = $row->status;
					$out['product_price_list'] = $price_list;
					array_push($new_list,$out);
				}

					if(!empty($all_filter)){
					
						$fill = [];
						$all_filter = implode(",",$all_filter);
						$all_filter = explode(",",$all_filter);
						$all_filter = implode(",",$all_filter);
						$all_filter = explode(", ",$all_filter);
						$all_filter = implode(",",$all_filter);
						$all_filter = explode(",",$all_filter);
					    $all_filter =  array_unique($all_filter);
						$all_filter = array_filter($all_filter);
						foreach($all_filter as $filter)
						{
							$filter_model = ProductFilter::find()->where(['name'=>$filter])->one();
							$fill['id'] = $filter_model->id;
							$fill['name'] = $filter_model->name;
							array_push($fillter,$fill);
						}
						//$main_fillter['filter_list'] = $fillter;
						//array_push($new_list,$main_fillter);
					}
			}
		
		$result["status"] = "000000";
		$result["msg"] = "Success";
		$result["product_list"] = isset($new_list) ? $new_list : null;
		$result["filter_list"] = isset($fillter) ? $fillter : null;
		return $result;	
	}
	
	public function actionGetSortList()
	{
		$result = [];
		$sorting_list = [];
		$sort_pair = ["1"=>"A to Z","2"=>"Z to A","3"=>"Min Offer","4"=>"Max Offer","5"=>"Price Low to High","6"=>"Price High to Low"];
		foreach($sort_pair as $key=>$value)
		{
			$sort_list['id'] = $key;
			$sort_list['name'] = $value;
			array_push($sorting_list,$sort_list);
		}
		
		$result["status"] = "000000";
		$result["msg"] = "Success";
		$result["sort_list"] = isset($sorting_list) ? $sorting_list : null;
		return $result;
	}		
	
}
