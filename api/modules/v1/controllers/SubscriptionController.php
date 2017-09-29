<?php

namespace api\modules\v1\controllers;

use yii\rest\ActiveController;
use backend\models\Role;
use backend\models\Subscription;
use common\models\User;
use backend\models\Users;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use yii\filters\AccessControl;
use backend\models\Products;
use backend\models\XrefPauseSubscription;
use backend\models\Delivery;
use backend\models\DefaultSetting;
use backend\models\ProductPrice;
use backend\models\AccountStatement;



/**
 * Route Controller API

 */
class SubscriptionController extends ActiveController 
{
    public $modelClass = 'backend\models\Subscription';
	
	
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
	
	public function actionList()
	{
		$result = [];
		$out = [];
        $new_list= [];
		$pause_list = [];
        if(!isset($_POST['user_id']) || !isset($_POST['start_date']) || !isset($_POST['end_date']))
		{
            return [
				"status" => "000001",
				"msg" => "user id or date not set" 
			];
		}
		
		$user_id = $_POST['user_id'];
		$start_date = $_POST['start_date'];
		$end_date = $_POST['end_date'];
		//$min_date = Subscription::find()->where(['user_id' => $user_id])->andWhere(['between', 'start_date', $start_date, $end_date ])->min('start_date');
		//$max_date = Subscription::find()->where(['user_id' => $user_id])->andWhere(['between', 'start_date', $start_date, $end_date ])->max('start_date');
		$subscription = Subscription::find()->where(['<=', 'start_date', $end_date])->andWhere(['>=', 'end_date', $start_date])->andWhere(['user_id' => $user_id])->asArray()->All();
		if(isset($subscription) && ($subscription != null))
		{
			foreach ($subscription as $row) 
			{
				/* $product = Products::find()->where(['id' => $row['product_id']])->asArray()->One();
				$out['product_name'] = $product['name']; */  
				//$out['start_date'] = $row['start_date'];
				if($row['start_date'] <= $start_date)
				{
					$out['start_date'] = $start_date;
				}
				else
				{
					$out['start_date'] = $row['start_date'];
				}
	
				if($row['end_date'] >= $end_date)
				{
					$out['end_date'] = $end_date;
				}
				else
				{
					$out['end_date'] = $row['end_date'];
				}
				array_push($new_list,$out);
			}

			$pause_items = XrefPauseSubscription::find()->where(['user_id' => $user_id,'type' => 'pause'])->andWhere(['<=', 'start_date', $end_date])->andWhere(['>=', 'end_date', $start_date])->asArray()->All();
			if(isset($pause_items) && ($pause_items != null))
			{
					foreach($pause_items as $pause_item)
					{
						if($pause_item['start_date'] <= $start_date)
						{
							$pause['start_date'] = $start_date;
						}
						else
						{
							$pause['start_date'] = $pause_item['start_date'];
						}
			
						if($pause_item['end_date'] >= $end_date)
						{
							$pause['end_date'] = $end_date;
						}
						else
						{
							$pause['end_date'] = $pause_item['end_date'];
						}
						
						array_push($pause_list,$pause);
					}	
			}
			
		}
		else{
			return [
				"status" => "000001",
				"msg" => "no subscription found" 
			];
			
		}

		$result["status"] = "000000";
		$result["msg"] = "Success";
		$result["subscription_list"] = isset($new_list) ? $new_list : null;
		$result["pause_list"] = isset($pause_list) ? $pause_list : null;
		//$result["edit_list"] = isset($edit_list) ? $edit_list : null;
		return $result;	
	}
	
	public function actionEditList()
	{
		$result = [];
		$edit_list = [];
        if(!isset($_POST['user_id']) || !isset($_POST['start_date']))
		{
            return [
				"status" => "000001",
				"msg" => "user id or start date not set" 
			];
		}
		
		$user_id = $_POST['user_id'];
		$start_date = $_POST['start_date'];
		$flag = 0;
		$subscription = Subscription::find()->where(['user_id' => $user_id/*, 'status'=>1 */])->andWhere(['<=', 'start_date', $start_date])->andWhere(['>=', 'end_date', $start_date])->asArray()->All();
		if(isset($subscription) && ($subscription != null))
		{
			foreach ($subscription as $row) 
			{
				 $date = new \DateTime($start_date);
				$now = new \DateTime();
				
				if($date < $now) {
					
					$delivery = Delivery::find()->where(['user_id' => $user_id,'subscription_id' => $row['id'],'delivery_date' => $start_date])->asArray()->One();
					if(isset($delivery))
					{
							$product = Products::find()->where(['id' => $row['product_id']])->asArray()->One();
							$delivery_status = DefaultSetting::find()->where(['type'=>'delivery','value'=>$delivery['isdeliver']])->one();
							$edit['subscription_id'] =	$delivery['subscription_id'];
							$edit['start_date'] =	$delivery['delivery_date'];
							$edit['product_name'] = $product['name'];
							$edit['cat_id'] = $product['cat_id'];
							$edit['type'] =	$row['type'];				
							$edit['quantity'] =	$delivery['delivered'];
							$edit['status'] =	$delivery_status->name;
							array_push($edit_list,$edit);
							$flag = 1;
					}
					else{
							$product = Products::find()->where(['id' => $row['product_id']])->asArray()->One();
							$delivery_status = DefaultSetting::find()->where(['type'=>'delivery','value'=>2])->one();
							$edit['subscription_id'] =	$row['id'];
							$edit['start_date'] =	$start_date;
							$edit['product_name'] = $product['name'];
							$edit['cat_id'] = $product['cat_id'];
							$edit['type'] =	$row['type'];				
							$edit['quantity'] =	0;
							$edit['status'] =	$delivery_status->name;
							array_push($edit_list,$edit);
							$flag = 1;
						
					}
				}
				else{
									
					$edit_items = XrefPauseSubscription::find()->where(['user_id' => $user_id,'subscription_id' => $row['id'],'start_date' => $start_date,'end_date' => $start_date])->asArray()->All();
					if(isset($edit_items) && ($edit_items != null))
					{
						foreach($edit_items as $edit_item)
						{
							
							//$subscription = Subscription::find()->where(['id' => $edit_items['subscription_id']])->asArray()->One();
							$product = Products::find()->where(['id' => $row['product_id']])->asArray()->One();
							
							$edit['subscription_id'] =	$edit_item['subscription_id'];
							$edit['start_date'] =	$edit_item['start_date'];
							$edit['product_name'] = $product['name'];
							$edit['cat_id'] = $product['cat_id'];
							$edit['type'] =	$edit_item['type'];				
							$edit['quantity'] =	$edit_item['quantity'];
							$edit['status'] =	'Pending';
							array_push($edit_list,$edit);
							$flag = 1;
						}	
					}
					else{
							
						if($row['start_date'] <= $start_date && $row['end_date'] >= $start_date)
						{
							$product = Products::find()->where(['id' => $row['product_id']])->asArray()->One();
							$edit['subscription_id'] =	$row['id'];
							$edit['start_date'] =	$row['start_date'];
							$edit['product_name'] = $product['name'];
							$edit['cat_id'] = $product['cat_id'];
							$edit['type'] =	$row['type'];				
							$edit['quantity'] =	$row['quantity'];
							$edit['status'] =	'Pending';
							array_push($edit_list,$edit);
							$flag = 1;
						}
		
					}
				}
			}
		}
		else{
			return [
				"status" => "000001",
				"msg" => "No subscription found for this date" 
			];
		}
		
		if($flag){
			$result["status"] = "000000";
			$result["msg"] = "Success";
			$result["subscription_list"] = !empty($edit_list) ? $edit_list : "No subscription found for this date ";
			return $result;	
		}
		else{
			return [
						"status" => "000001",
						"msg" => "No subscription found for this date" 
					];
		}
	}

	public function actionSubscriptionUpdate()
	{
		$result = [];
		$out = [];
        $new_list= [];
		$flag = false;
        if(!isset($_POST['subscriptions']) || !isset($_POST['start_date']) || !isset($_POST['user_id']))
		{
            return [
				"status" => "000001",
				"msg" => "some post value not set" 
			];
		}
		
		$subscriptions = json_decode($_POST['subscriptions'],true);
		$user_id = $_POST['user_id'];
		if(is_array($subscriptions))
		{
			foreach ($subscriptions as $subscription)
			{
				/* if($subscription['quantity'] == 0)
				{
					$type = 'pause';
				}
				else{
					$type = 'edit';
				} */
				
				$model = XrefPauseSubscription::find()->where(['subscription_id' => $subscription['id'],'start_date' => $_POST['start_date'],'user_id' => $user_id])->One();
				
				if(!isset($model) || $model == null)
				{
					$model = new XrefPauseSubscription();
					
				}
				$model->subscription_id = $subscription['id'];
				$model->start_date = $_POST['start_date'];
				$model->end_date = $_POST['start_date']; 
				$model->quantity = $subscription['quantity'];
				$model->type = "edit"; //$type;
				$model->user_id = $user_id;
				$model->status = 1;
				
				if(!$model->save())
				{
					$flag = true;
				}
				
			}
		}
		
		if($flag)
		{
			$result["status"] = "000001";
			$result["msg"] = "Some value may not updated or added. Please try again !";
			return $result;	
		}
		$result["status"] = "000000";
		$result["msg"] = "Success";
		return $result;	
	}

	public function actionAdd()
	{
		$result = [];
		$out = [];
        $new_list= [];
        if(!isset($_POST['user_id']) || !isset($_POST['product_id']) || !isset($_POST['start_date'])||  !isset($_POST['quantity']) || !isset($_POST['type']))
		{
            return [
				"status" => "000001",
				"msg" => "some post value not set" 
			];
		}
		
		$user_id = $_POST['user_id'];
	 	$sub_model = Subscription::findSubscription($_POST['product_id'],$_POST['user_id']);
		if(isset($sub_model) && $sub_model->type == 'Daily'){
			return [
				"status" => "000001",
				"msg" => "Already Subscribed." 
			];
		} 
		$pause_items = XrefPauseSubscription::find()->where(['user_id' => $user_id,'start_date' => $_POST['start_date'],'type'=>'pause'])->asArray()->All();
		if(isset($pause_items) && ($pause_items != null))
		{
			foreach($pause_items as $pause_item)
			{
				$pausemodel = XrefPauseSubscription::find()->where(['id' => $pause_item['id']])->One();				
				$pausemodel->type = "edit";
				$pausemodel->save();
			}
		}
		
		$subsc_model = new Subscription();
		$subsc_model->product_id = $_POST['product_id'];  
		$subsc_model->start_date = $_POST['start_date'];
		if($_POST['type'] == 'One Time'){	
			$subsc_model->end_date = $_POST['start_date'];
		}
		else{
			$subsc_model->end_date = date('Y-m-d',strtotime('+1 year',strtotime($_POST['start_date'])));
		}
		
		$productprice = ProductPrice::find()->where(['status'=> 1,'product_id'=>$_POST['product_id']])->one();
		if(isset($productprice))
		{
			$subsc_model->mrp = $productprice->mrp;
			$subsc_model->unit = $productprice->unit;
		}
		else{
				$product = Products::findOne($_POST['product_id']);
				$subsc_model->mrp = $product->price;
				$subsc_model->unit = 'Ltr';
			}
		$subsc_model->type = $_POST['type'];
		$subsc_model->quantity = $_POST['quantity'];
		$subsc_model->user_id = $_POST['user_id'];
		$subsc_model->status = 1;
		//return $model;
		if(!$subsc_model->save())
		{
			return [
				"status" => "000001",
				"msg" => "Subscription not added, try again !" 
			];
		}
		$product = Products::find()->where(['id' => $subsc_model->product_id])->One();
		$product->popularity = $product->popularity + 1;
		$product->save();
		$result["status"] = "000000";
		$result["msg"] = "Success";
		$result["subscription_list"] = $subsc_model;
		return $result;	
	}

	public function actionPauseSubscription()
	{
		$result = [];
		$new_list = [];
            
         if(!isset($_POST['user_id']) || !isset($_POST['start_date']) || !isset($_POST['end_date']))
		 {
            return [
				"status" => "000001",
				"msg" => "user id or start date or end date not set" 
			];
		 }
	    $user_id = $_POST['user_id'];
		$current_date = date("Y-m-d");
		if(strtotime($current_date) > strtotime($_POST['start_date']))
		{
			return [
				"status" => "000001",
				"msg" => "past date given as start date for pause " 
			];
		}
		$start_date = $_POST['start_date'];
		$end_date = $_POST['end_date'];
		$start = strtotime($_POST['start_date']);
		$end = strtotime($_POST['end_date']);
		$no_days = floor(abs($end - $start) / 86400) + 1;
		$pause_status = 0;
		$date = $_POST['start_date'];
		$i = 1;
		$multiple_pause = 0;
		$flag = 0;
		
		if(isset($_POST['subscriptions']))
		{
			$subscriptions = json_decode($_POST['subscriptions'],true);
			if(is_array($subscriptions))
			{
				$multiple_pause = 1;
			}
			else{
				return [
				"status" => "000001",
				"msg" => "product subscription not valid " 
				];
				
			}
 
		}
		
		if($multiple_pause){

			
				foreach ($subscriptions as $subscription)
				{
					$subs_model = Subscription::find()->where(['<=', 'start_date', $end_date])->andWhere(['>=', 'end_date', $start_date])->andWhere(['user_id' => $user_id, 'id'=>$subscription['id'] ])->asArray()->One();
					
					if(isset($subs_model))
					{
						$date = $_POST['start_date'];
						$i = 1;
						while($no_days >= $i)
						{
							if($subs_model['type'] == 'Daily'){
								$pause_items = XrefPauseSubscription::find()->where(['subscription_id' => $subs_model['id'],'user_id' => $user_id,'start_date' => $date])->One();
								if(!isset($pause_items))
								{
									$model = new XrefPauseSubscription();
									$model->subscription_id = $subscription['id'];  
									$model->start_date = $date;
									$model->end_date = $date;
									$model->user_id = $user_id;
									$model->quantity = $subscription['qty'];
									$model->type = 'pause';
									$model->save();
									$pause_status = 1;			
								}
								else{
									$pause_items->status = 1;
									$pause_items->type = 'pause';
									$pause_items->save();
									$pause_status = 1;
								}
								
							}
							else{
								
								$pause_items = XrefPauseSubscription::find()->where(['subscription_id' => $subs_model['id'],'user_id' => $user_id,'start_date' => $date])->One();
								
								if(!isset($pause_items))
								{
									if($subs_model['start_date'] == $date){
										$model = new XrefPauseSubscription();
										$model->subscription_id = $subscription['id'];  
										$model->start_date = $date;
										$model->end_date = $date;
										$model->user_id = $user_id;
										$model->quantity = $subscription['qty'];
										$model->type = 'pause';
										$model->save();
										$pause_status = 1;	
										break;	
									}									
								}
								else{
									$pause_items->status = 1;
									$pause_items->type = 'pause';
									$pause_items->save();
									$pause_status = 1;
									break;
								}
								
								
							}
							$date = date('Y-m-d', strtotime($date .' +1 day'));
							$i++;
						}
						
						
					 }
										
				}
				
			if($pause_status){
				
				$result["status"] = "000000";
				$result["msg"] = "Subscription pause successfully.";
				return $result;
			}
			else{
				$result["status"] = "000001";
				$result["msg"] = "No subscription found ";
				return $result;
			}
		}
		else{
			while($no_days >= $i)
			{	
				$subscription = Subscription::find()->where(['<=', 'start_date', $end_date])->andWhere(['>=', 'end_date', $start_date])->andWhere(['user_id' => $user_id])->asArray()->All();
				if(isset($subscription) && (!empty($subscription)))
				{
					foreach ($subscription as $row) 
					{
						if($row['start_date'] <= $start_date)
						{
							$new_start = $start_date;
						}
						else
						{
							$new_start = $row['start_date'];
						}
			
						/* if($row['end_date'] >= $end_date)
						{
							$new_end = $end_date;
						}
						else
						{
							$new_end = $row['end_date'];
						} */
						
						$model = XrefPauseSubscription::find()->where(['subscription_id' => $row['id'],'user_id' => $user_id,'start_date' => $new_start])->One();
						
						if(!isset($model))
						{
							$model = new XrefPauseSubscription();
							$model->subscription_id = $row['id'];  
							$model->start_date = $new_start;
							$model->end_date = $new_start;
							$model->user_id = $user_id;
							$model->quantity = $row['quantity'];
							$model->type = 'pause';
							$model->save();
							$pause_status = 1;			
						}
						else{
							if($model->type == 'edit')
							{
								$model->type = 'pause';
							}
							else{
								$model->type = 'edit';
							}
								
							$model->status = 1;
							$model->save();
							$pause_status = 1;
						}					
					}
				}
				else
				{
					$result["status"] = "000001";
					$result["msg"] = "No subscription found ";
					return $result;
				}
				$date = date('Y-m-d', strtotime($date .' +1 day'));
				$i++;
			}
			if($pause_status){
				if($model->type =='pause')
				{
					$result["status"] = "000000";
					$result["msg"] = "Subscription pause successfully.";
				}
				else{
						$result["status"] = "000005";
						$result["msg"] = "Subscription unpause successfully.";
					}
				
				
				return $result;
			}
			else{
				$result["status"] = "000001";
				$result["msg"] = "No subscription found ";
				return $result;
			}

		}
			
	}


	public function actionUnsubscribe()
	{
		$result = [];
		$out = [];
        $new_list= [];
        if(!isset($_POST['subscription_id']))
		{
            return [
				"status" => "000001",
				"msg" => "subscription id can't be empty." 
			];
		}
		
		$subscription_id = $_POST['subscription_id'];
		
		$model = Subscription::find()->where(['id' => $subscription_id,'status' => 1])->One();
		if(isset($model))
		{
			 date_default_timezone_set("Asia/Kolkata");
			 
			$acc_model = new AccountStatement();
			$account_details = $acc_model->getPendingBillAmount($model->user_id);
			$pending_amount = $account_details['pending_amount'];
			if($pending_amount > 0)
			{
				$model->status = 2;   // unsettled subscription
			}
			else{
				if (date('H') >= 19) { 
				 $end_date = date('Y-m-d',strtotime('+2 day',strtotime(date('Y-m-d')))); 	
				}
				else{
					 $end_date = date('Y-m-d',strtotime('+1 day',strtotime(date('Y-m-d'))));
				} 
				$model->end_date = $end_date;
				$model->status = 0;   // inactive subscribe
			}
			
			if($model->save())
			{
				$xref_subscriptions = XrefPauseSubscription::find()->where(['subscription_id' => $subscription_id])->andwhere(['>=', 'end_date', $end_date])->All();
				if(isset($xref_subscriptions))
				{	
					foreach ($xref_subscriptions as $xref) 
					{
						$xref->delete();
					}
				}
				
				$model->deleteExtraDelivery();
				$result["status"] = "000000";
				$result["msg"] = "Success";
			}
			else{
				$result["status"] = "000001";
				$result["msg"] = "some thing went wrong, try again!";
			}
			
		}
		else{
			$result["status"] = "000001";
			$result["msg"] = "Subscription Id not valid or subscription already unsubscribed.";
		}
		//$result["subscription_list"] = $model;
		return $result;	
	}
	
	public function actionProductForPause()
	{
		$result = [];
		$out = [];
        $new_list= [];
		$flag = 0;
        if(!isset($_POST['user_id']))
		{
            return [
				"status" => "000001",
				"msg" => "user id not set" 
			];
		}
		
		$user_id = $_POST['user_id'];
		$start_date = date('Y-m-d');
		$subscription = Subscription::find()->where(['user_id' => $user_id,'status' => 1])->andWhere(['>=', 'end_date', $start_date])->asArray()->All();
		if(isset($subscription) && ($subscription != null))
		{
			foreach ($subscription as $row) 
			{
				$product = Products::find()->where(['id' => $row['product_id']])->asArray()->One();
				$out['subscription_id'] =	$row['id'];
				$out['start_date'] =	$row['start_date'];
				$out['end_date'] =	$row['end_date'];
				$out['product_name'] = $product['name'];
				$out['cat_id'] = $product['cat_id'];
				$out['type'] =	$row['type'];				
				$out['quantity'] =	$row['quantity'];
				array_push($new_list,$out);
				$flag = 1;
			}
			
		}
		else{
			return [
				"status" => "000001",
				"msg" => "No subscribed products found" 
			];
			
		}
		if($flag){
			$result["status"] = "000000";
			$result["msg"] = "Success";
			$result["subscription_list"] = !empty($new_list) ? $new_list : "No subscribed products found ";
			return $result;	
		}
		else{
			return [
						"status" => "000001",
						"msg" => "No subscribed products found" 
					];
		}

	}
	
	// function for edit subscription quantity
	public function actionChangeQuantity()
	{
		$result = [];
		$out = [];
        $new_list= [];
        if(!isset($_POST['user_id']) || !isset($_POST['subscription_id']) ||  !isset($_POST['quantity']))
		{
            return [
				"status" => "000001",
				"msg" => "some post value not set" 
			];
		}
		
		$user_id = $_POST['user_id'];
		$subscription_id = $_POST['subscription_id'];
		
		$model = Subscription::find()->where(['user_id' => $user_id,'status' => 1,'id'=>$subscription_id])->One();
		if($model){
			$model->quantity = $_POST['quantity'];
			if(!$model->save())
			{
				return [
					"status" => "000001",
					"msg" => "subscription quantity not updated, try again !" 
				];
			}
			else{
				$result["status"] = "000000";
				$result["msg"] = "Success";
				$result["subscription_list"] = $model;
			}
		}
		else{
			$result["status"] = "000001";
			$result["msg"] = "subscribed product not found.";
		}
		
		return $result;	
	}
	
	/**
     * Update XrefPauseSubscription models from sms api .
     * @return mixed
     */
	public function actionShortSmsResponse() 
    {

        //$inNumber = $_REQUEST["inNumber"];
		 $sender = $_REQUEST["sender"];
		 $content = $_REQUEST["content"];
		/* $keyword = $_REQUEST["keyword"];
		  $email = $_REQUEST["email"];
		 $credits = $_REQUEST["credits"]; */
		 $sender = substr($sender,2);
		 $content = substr($content,7);
		 $user = User::find()->where(['username'=>$sender])->one();
			if(isset($user))
			{
				$subscription = Subscription::find()->where(['user_id'=>$user->id,'status'=>1,'product_id'=>1])->orderBy('id desc')->limit(1)->one(); 
				if(isset($subscription))
				{
					
					//$old_quantity = $subscription->quantity;
					$new_quantity = $content;
					if(is_numeric($content) && preg_match('/^\d+$/', $content))
					{
						$date = date('Y-m-d',strtotime('+1 day',strtotime(date('Y-m-d'))));
						if($content != 0 )
						{
							$type = 'edit';
						}
						else{
							$type = 'pause';
						}
						$pause_items = XrefPauseSubscription::find()->where(['subscription_id' => $subscription->id,'user_id' => $user->id,'start_date' => $date])->One();
						if(!isset($pause_items))
						{
							$pause_items = new XrefPauseSubscription();
							$pause_items->subscription_id = $subscription->id;  
							$pause_items->start_date = $date;
							$pause_items->end_date = $date;
							$pause_items->user_id = $user->id;
							$pause_items->quantity = $content;
							$pause_items->type = $type;
							$pause_items->save();			
						}
						else{
							$pause_items->quantity = $content;
							$pause_items->type = $type;
							$pause_items->save();
						}
						$area_discount = !empty($subscription->area_discount)? $subscription->area_discount : 0;
						$delivery_item = Delivery::find()->where(['subscription_id' => $subscription->id,'delivery_date' => $date])->One();
							if($delivery_item){
								$delivery_boy = Users::findOne($delivery_item->user_id);
								
								$delivery_item->quantity = $content;
								$delivery_item->delivery_boy_id = $delivery_boy->delivery_boy_id;
								$address_id = isset($delivery_boy->staff) ? $delivery_boy->staff->address_id : '';
								$delivery_item->address_id = $address_id;
								
								$delivery_item->save();
							}
							else{
								$model = new Delivery();
								$delivery_boy = Users::findOne($subscription->user_id);
								$address_id = isset($delivery_boy->staff) ? $delivery_boy->staff->address_id : '';
								$model->subscription_id = $subscription->id;
								$model->product_id = $subscription->product_id;
								$model->area_discount = $area_discount;
								$model->user_id = $subscription->user_id;
								$model->quantity = $content;
								$model->delivery_date = $date;
								$model->address_id = $address_id;
								$model->isdeliver = 1;
								$model->delivery_boy_id = $delivery_boy->delivery_boy_id;
								$productprice = ProductPrice::find()->where(['status'=> 1,'product_id'=>$subscription->product_id])->one();
								if(isset($productprice))
								{
									$model->mrp = $productprice->mrp;
									$model->unit = $productprice->unit;
								}
								$model->save();
							}
						$date = date('d-m-Y',strtotime('+1 day',strtotime(date('d-m-Y'))));
						$user->sendSmsTemplate($user->username,$date,$new_quantity);
					}
					else{
						$this->sendSms($sender,$sub = 0, $con = 1);
					}
				}
				else{
					$this->sendSms($sender,$sub = 1, $con = 0);
				}
			}
			else{
				$this->sendSms($sender,$sub = 0, $con = 0);
			}
			return true;
    }
	public function checkcontains($needle, $haystack)
    {
        return strpos($haystack, $needle) !== false;
    }
	
	public function sendSms($mobile,$sub,$con)
	{
		$api_url = DefaultSetting::getConfigByName("SMS_API_URL");
				$api_username = DefaultSetting::getConfigByName("SMS_API_USER");
				$api_pwd = DefaultSetting::getConfigByName("SMS_API_PWD");
				$api_sender_id = DefaultSetting::getConfigByName("SMS_SENDER_ID");
				$user_name = "Customer";
				if($con == 1)
				{
					$message_text = "Dear Customer, Request FAILED. Invalid quantity. Sample DOODVL 3 Thanks. doodhvale.com";
				}
				elseif($sub == 1)
				{
					$message_text = "Dear Customer, Request FAILED. You have not subscribed for daily milk delivery. Download app to subscribe. Thanks. doodhvale.com";
				}
				else
				{
					$message_text = "Dear Customer, Request FAILED. Please resend your request from your registered mobile number.Thanks. doodhvale.com";
			
				}
				$ch = curl_init();
				$user=$api_username.":".$api_pwd;
					

				curl_setopt($ch,CURLOPT_URL, $api_url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, "user=$user&senderID=$api_sender_id&receipientno=$mobile&msgtxt=$message_text");
				$buffer = curl_exec($ch);
				
				if(empty($buffer))
				{
						return false;
				}
				else
				{
					if($this->checkcontains('Status=1',$buffer))
					{
						return false;
						mail('pawan.shukla@subtlelabs.com', 'SMS Sending Error', $buffer." ".$receipientno." ".$msgtxt);
					}
					else
					{
						return true;
					}
				}
				curl_close($ch);
	}

	 
}
