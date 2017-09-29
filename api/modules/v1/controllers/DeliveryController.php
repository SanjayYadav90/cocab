<?php

namespace api\modules\v1\controllers;

use yii\rest\ActiveController;
use backend\models\Role;
use backend\models\Subscription;
use common\models\User;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use yii\filters\AccessControl;
use backend\models\Products;
use backend\models\XrefPauseSubscription;
use backend\models\Delivery;
use backend\models\DefaultSetting;
use backend\models\Users;
use backend\models\Address;
use backend\models\TrackDeliveryBoy;
use backend\models\TrackHistory;
use backend\models\Route;
use backend\models\RouteMap;
use backend\models\DeliveryAnalytics;
use backend\models\AccountStatement;
use backend\models\CowMilkBilling;

/**
 * Delivery Controller API

 */
class DeliveryController extends ActiveController 
{
    public $modelClass = 'backend\models\Delivery';
	
	
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
	
	public function actionCustomerList()
	{
		$result = [];
		$out = [];
        $new_list= [];
        if(!isset($_POST['dboy_id']) || !isset($_POST['d_date']) || !isset($_POST['product_id']))
		{
            return [
				"status" => "000001",
				"msg" => "Delivery boy id or delivery date or product id not set" 
			];
		}
		
		$dboy_id = $_POST['dboy_id'];
		$d_date = $_POST['d_date'];
		$date = new \DateTime($d_date);
		 $date = $date->format('Y-m-d');
		$now = new \DateTime();
		 $now = $now->format('Y-m-d');
		if($date != $now)
		{
			return [
				"status" => "000001",
				"msg" => "current date not set or current date not match to given date." 
			];
		}
		
		$product_id = $_POST['product_id'];
		$tot_empty_bottle = 0;
		$total_delivered = 0;
		$total_pending = 0;
		$check_amt = 0;
		$cash_amt = 0;
		$del_pending_amount  = 0;    //get total pending amount which is collected by delivery boy
		
		$order = Delivery::find()
			->JoinWith('routemap', 'Delivery.user_id = RouteMap.user_id')
			->andWhere(['product_id'=>$product_id, 'delivery_boy_id' => $dboy_id])
			->andWhere(['=','delivery_date', $d_date])
			->orderBy('sequence Asc, subscription_id ASC')
			->asArray()
			->All();

		if(isset($order) && ($order != null))
		{
			foreach ($order as $row) 
			{
				
				$user = Users::find()->where(['id' => $row['user_id']])->one();
				$out['d_date'] =	$row['delivery_date'];
				$out['delivery_id'] =	$row['id'];
				//$out['subscription_id'] =	$row['subscription_id'];
				$out['delivery_status'] =	$row['isdeliver'];
				$out['mobile'] =	$user->username;
				$out['customer_id'] =	$user->id;
				$out['quantity'] = $row['quantity'];
				$out['unsettled'] = $row['unsettled'];
				

				$total_pending = $total_pending + $row['quantity'];
				if($row['isdeliver'] == 2){
					$total_delivered = $total_delivered +  (isset($row['delivered']) ? $row['delivered'] : 0);
				}
				$tot_empty_bottle = $tot_empty_bottle + (isset($row['empty_bottle']) ? $row['empty_bottle'] : 0) ;
				
				if(isset($user->staff)){
					$out['username'] =	$user->staff->first_name .' '. $user->staff->last_name;
					$out['address'] =	$user->staff->address->address1 .' '. $user->staff->address->address2.' '. $user->staff->address->city.' '. $user->staff->address->pincode;
					$out['latitude'] =	$user->staff->address->latitude;
					$out['longitude'] =	$user->staff->address->longitude;
				}
				$today_amount = AccountStatement::find()->select('amount,payment_mode')->where(['user_id' => $row['user_id'],'type'=>'Cr.'])->andWhere(['IN','payment_mode',['Cash','Cheque']])->andWhere(['=','transaction_date', $d_date])->groupby('user_id')->one();
				
			    $today_collection = isset($today_amount->amount) ? $today_amount->amount :0;
				$out['today_collection'] =	$today_collection;
				$out['payment_type'] =	isset($today_amount->payment_mode) ? $today_amount->payment_mode : '';
				
				$acc_mod = new AccountStatement();
				$pending_cust = $acc_mod->getPendingBillAmountReport($row['user_id'],$dboy_id,1);	
				$out['pending_customer'] = $pending_cust['pending_customer'];
				$out['pending_amount'] = $pending_cust['pending_amount'];
				$del_pending_amount= $del_pending_amount + $pending_cust['pending_amount'];   //get total pending amount which is collected by delivery boy
				
				array_push($new_list,$out);
				
			}
			
			$account_cash = AccountStatement::find()->select('sum(amount) as amount')->where(['delivery_boy_id' => $dboy_id,'payment_mode'=>'Cash','payment_status'=>'Received','type'=>'Cr.'])->andWhere(['=','transaction_date', $d_date])->groupby('delivery_boy_id')->one();
			if(isset($account_cash))
			{
				$cash_amt = isset($account_cash->amount) ? $account_cash->amount :0;
			}
			$account_cheque = AccountStatement::find()->select('sum(amount) as amount')->where(['delivery_boy_id' => $dboy_id,'payment_mode'=>'Cheque','payment_status'=>'Pending','type'=>'Cr.'])->andWhere(['=','transaction_date', $d_date])->groupby('delivery_boy_id')->one();
			if(isset($account_cheque))
			{
				$check_amt = isset($account_cheque->amount) ? $account_cheque->amount :0;
			}
		}
		else{
			
			$result["status"] = "000001";
			$result["msg"] = "no delivery found for this date";
			$result["delivery_list"] = isset($new_list) ? $new_list : null;
			return $result;
			
		} 
		if($total_pending > 0)
		{
			$total_pending = $total_pending - $total_delivered;
		}			
		
		$result["status"] = "000000";
		$result["msg"] = "Success";
		$result["delivery_list"] = isset($new_list) ? $new_list : null;
		$result["total_pending"] = $total_pending  ;
		$result["total_delivered"] = $total_delivered;
		$result["tot_empty_bottle"] = $tot_empty_bottle;
		$result["check_amt"] = $check_amt  ;
		$result["cash_amt"] = $cash_amt;
		$result["del_pending_amount"] = $del_pending_amount;
		return $result;	
	}
	
	public function actionDeliveryProductDetails()
	{
		$result = [];
		$out = [];
        $new_list= [];
        if(!isset($_POST['delivery_id']))
		{
            return [
				"status" => "000001",
				"msg" => "Delivery id  not set" 
			];
		}
		
		$delivery_id = $_POST['delivery_id'];

		$order = Delivery::find()->where(['id'=>$delivery_id])->asArray()->One();
		if(isset($order) && ($order != null))
		{
			$product = Products::find()->where(['id' => $order['product_id']])->asArray()->One();
			//$user = Users::find()->where(['id' => $row['user_id']])->one();
			$out['d_date'] =	$order['delivery_date'];
			$out['delivery_status'] =	$order['isdeliver'];
			$out['pending_bottle'] =	$order['pending_bottle'];
			$out['empty_bottle'] =	$order['empty_bottle'];
			$out['broken_bottle'] =	$order['broken_bottle'];
			//$out['subscription_id'] =	$order['subscription_id'];
			$out['product_name'] = $product['name'];	
			if($order['isdeliver'] == 1){
					
					$out['quantity'] =	$order['quantity'];
				}
				else{
					$out['quantity'] =	isset($order['delivered']) ? $order['delivered'] : $order['quantity'] ;
				}
			//$out['default_quantity'] =	$user->username;
			$out['customer_id'] =	$order['user_id'];
			
			// added 1 day into current time for tommorow order quantity
			$now = date('Y-m-d',strtotime('+1 day',strtotime(date('Y-m-d'))));
			$edit_items = XrefPauseSubscription::find()->where(['user_id' => $order['user_id'],'subscription_id' => $order['subscription_id'],'start_date' => $now,'end_date' => $now])->asArray()->one();
			
			if(isset($edit_items) && ($edit_items != null))
			{
					//return print_r($edit_items);
					$out['default_quantity'] =	$edit_items['quantity'];
			}
			else{
					
					$subscription = Subscription::find()->where(['id' => $order['subscription_id']])->asArray()->One();				
					$out['default_quantity'] =	$subscription['quantity'];
			} 
			
			$acc_model = new AccountStatement();
			$account_details = $acc_model->getPendingBillAmountReport($order['user_id'],0);
			$out['pending_amount'] = $account_details['pending_bill_amount'];
			$out['bill_amount'] = $account_details['net_payable_amount'];
			$out['outstanding_amount'] = $account_details['outstanding_amount'];
		}
		else{
			return [
				"status" => "000001",
				"msg" => "no delivery item found"
			];
			
		} 

		$result["status"] = "000000";
		$result["msg"] = "Success";
		$result["order_details"] = isset($out) ? $out : null;
		return $result;	
	}

	///// delivery status only for delivery boy appwhen quantity = 0 /////
	public function actionDeliveryStatus()
	{
		$status = DefaultSetting::find()->select(['value','name'])->where(['type' => 'delivery'])->andWhere(['subject' =>'reason'])->orderBy('name ASC')->All();
		$result["status"] = "000000";
		$result["msg"] = "Success";
		$result["delivery_status"] = isset($status) ? $status : null;
		return $result;	
	}

    //// Adding map address of customer by delivery boy   ////// 
	public function actionAddCoordinateMap()
	{
		if(!isset($_POST['customer_id']) || !isset($_POST['latitude']) || !isset($_POST['longitude']))
		{
            return [
				"status" => "000001",
				"msg" => "customer id  or latitude / longitude not set" 
			];
		}
		$result = [];
		$user = Users::find()->where(['id' => $_POST['customer_id']])->one();
		$address_id = isset($user->staff) ? $user->staff->address_id : null;
		if(isset($address_id))
		{
			$model = Address::find()->where(['id' => $address_id])->One();
			$model->latitude = $_POST['latitude'];
			$model->longitude = $_POST['longitude'];
			$model->save();
			$result["status"] = "000000";
			$result["msg"] = "Success";
			$result["address"] = 'Successfully Saved User location';
			return $result;
		}
		else{
			return [
				"status" => "000001",
				"msg" => "profile not found for this user" 
			];
		}
			
	}
	////// Api for mark delivery status and delivered quantity by delivery boy   //////
	public function actionDeliveryUpdate()
	{
		 if(!isset($_POST['delivery_id']) || !isset($_POST['status']) || !isset($_POST['quantity']) || !isset($_POST['default_quantity']) || !isset($_POST['delivery_time']))
		{
            return [
				"status" => "000001",
				"msg" => "delivery id or delivery status or quantity or tommorrow quantity or delivery time not set" 
			];
		} 
		
		$delivery_time = $_POST['delivery_time'];
		$date = new \DateTime($delivery_time);
		$date = $date->format('Y-m-d');
		$now = new \DateTime();
		$now = $now->format('Y-m-d');
		 if($date != $now)
		{
			return [
				"status" => "000001",
				"msg" => "current date not set or current date not match to given date." 
			];
		} 
		$result = [];
		$model = Delivery::find()->where(['id' => $_POST['delivery_id']])->one();
		
		$model->isdeliver =  $_POST['status'];
		$model->delivered = $_POST['quantity'];
		$model->delivery_time = $delivery_time;
		$model->empty_bottle = isset($_POST['empty_bottle']) ? $_POST['empty_bottle'] : 0;
		$model->broken_bottle = isset($_POST['broken_bottle']) ? $_POST['broken_bottle'] : 0;
		$yesterday = date('Y-m-d',strtotime('-1 day',strtotime(date('Y-m-d'))));
		$tomorrow = date('Y-m-d',strtotime('+1 day',strtotime(date('Y-m-d'))));
		$pre_delivery	= Delivery::find()->where(['product_id'=>$model->product_id,'user_id'=>$model->user_id, 'delivery_boy_id' => $model->delivery_boy_id])->andWhere(['=','delivery_date', $yesterday])->asArray()->one();
		
		$model->pending_bottle = $model->delivered + ((isset($pre_delivery['pending_bottle']) ? $pre_delivery['pending_bottle'] : 0)-((isset($_POST['empty_bottle']) ? $_POST['empty_bottle'] : 0) + (isset($_POST['broken_bottle']) ? $_POST['broken_bottle'] : 0)));
		$model->save();
		$message_text = "Dear Customer ".$model->delivery_date." , Delivered: ".$model->delivered." Lts , Empty Collected: ".$model->empty_bottle." , Order ".$tomorrow." : ".$_POST['default_quantity']." Lts . Download doodhvale.com android mobile app and manage hassle free daily milk delivery.";
        User::sendSms($model->user->username,$message_text);
		// if the subscription is unsettle but delivery boy deliver the milk to customer then 
		// change the subscription from unsettle to active subscription.
		
		$sub_model = Subscription::findOne($model->subscription_id);
		if(isset($sub_model) && $sub_model->status == 2 && $model->delivered > 0)
		{
			$sub_model->status = 1;
			$sub_model->save();
		}
		$address_model = isset($model->user->staff) ? $model->user->staff->address : null;
		if(isset($address_model) && isset($address_model->latitude) && isset($address_model->longitude))
		{
			$distance = 0;
			$last_record = 0;
			$unit = 'K';
			$order	= Delivery::find()->where(['product_id'=>$model->product_id,'user_id'=>$model->user_id, 'delivery_boy_id' => $model->delivery_boy_id, 'isdeliver' => 1])->andWhere(['=','delivery_date', $now])->asArray()->one();
			if(!isset($order))
			{
				$last_record = 1;
			}				
			$analytics_model = DeliveryAnalytics::find()->where(['delivery_boy_id' => $model->delivery_boy_id,'travel_date'=> $now])->one();
			if(!isset($analytics_model) || $analytics_model == null)
			{
				$analytics_model = new DeliveryAnalytics();
				$route = Route::find()->where(['delivery_boy_id' => $model->delivery_boy_id])->one();
				if(isset($route))
				{
					 $startlocation = explode(',',$route->start_position);
					
					$new_distance = $this->distance($address_model->latitude, $address_model->longitude, $startlocation[0], $startlocation[1], $unit);
				}
				
			}
			else{
				//$distance = $analytics_model->distance;
				
				if($last_record != 1)
				{
					$last_location = explode(',' , $analytics_model->last_location);
					$new_distance = $this->distance($address_model->latitude, $address_model->longitude, $last_location[0], $last_location[1], $unit);
				}
				else
				{
					$route = Route::find()->where(['delivery_boy_id' => $model->delivery_boy_id])->one();
					if(isset($route))
					{
						$lastlocation = explode(',',$route->end_position);
						$new_distance = $this->distance($address_model->latitude, $address_model->longitude, $lastlocation[0], $lastlocation[1], $unit);
					}
					
					$track_history = TrackHistory::find()->where(['delivery_boy_id' => $model->delivery_boy_id])->andWhere(['between','date_time', $now.' 00:00:00',$now.' 23:59:59'])->orderBy('date_time ASC')->one();
					$start_datetime = strtotime($track_history->date_time);
					$current_datetime = strtotime(date('Y-m-d H:i:s'));
					$time_diff = round(abs($start_datetime - $current_datetime) / 60,2);    ///total time calulated in minute
					$analytics_model->total_time = $time_diff;
				}
			} 
			
			$distance = (isset($analytics_model->distance) ? $analytics_model->distance : 0) + $new_distance;
			$analytics_model->travel_date = $now;
			$analytics_model->delivery_boy_id = $model->delivery_boy_id;
			$analytics_model->distance = $distance ; 
			$analytics_model->unit = $unit;
			$analytics_model->last_location = $address_model->latitude .',' .$address_model->longitude;
			$analytics_model->save();
			
		}  
		$now = date('Y-m-d',strtotime('+1 day',strtotime(date('Y-m-d'))));
		$edit_model = XrefPauseSubscription::find()->where(['user_id' => $model['user_id'],'subscription_id' => $model['subscription_id'],'start_date' => $now,'end_date' => $now])->one();
		if(!isset($edit_model) || $edit_model == null)
		{
			$edit_model = new XrefPauseSubscription();
			
		}
		$edit_model->subscription_id = $model->subscription_id;
		$edit_model->start_date = $now;
		$edit_model->end_date = $now; 
		$edit_model->quantity = $_POST['default_quantity'];
		if($_POST['default_quantity'] == 0)
		{
			$edit_model->type = "pause"; //$type;
		}
		else{
			$edit_model->type = "edit"; //$type;
		}
		$edit_model->user_id = $model->user_id;
		$edit_model->status = 1;
		$edit_model->save();
		
		$result["status"] = "000000";
		$result["msg"] = "Success";
		$result["delivery"] = 'Successfully Saved delivery';
		return $result;	
	}
		
		
		
	/////// Tracking  delivery  boy location  saved into 
	public function actionCustomerLocation()
	{
		if(!isset($_POST['dboy_id']) || !isset($_POST['d_date']) || !isset($_POST['location']))
		{
            return [
				"status" => "000001",
				"msg" => "Delivery boy id or delivery date or location not set" 
			];
		}
		
		$dboy_id = $_POST['dboy_id'];
		$d_date = $_POST['d_date'];
		$date = new \DateTime($d_date);
		$date = $date->format('Y-m-d');
		$now = new \DateTime();
		 $now = $now->format('Y-m-d');
		if($date != $now)
		{
			return [
				"status" => "000001",
				"msg" => "current date not set or current date not match to given date." 
			];
		}
		$result = [];
		$model = TrackDeliveryBoy::find()->where(['delivery_boy_id' => $dboy_id])->andWhere(['between','date_time', $now.' 00:00:00',$now.' 23:59:59'])->one();
		if(!isset($model))
		{
			$model = new TrackDeliveryBoy();
		}
		$trackhistory = new TrackHistory();
		$model->delivery_boy_id = $dboy_id;
		$model->date_time = $d_date;
		$model->position = $_POST['location'];
		$trackhistory->delivery_boy_id = $dboy_id;
		$trackhistory->date_time = $d_date;
		$trackhistory->position = $_POST['location'];
		
		$model->save();
		$trackhistory->save();
		
		return [
				"status" => "000000",
				"msg" => "successfully saved." 
			];
			
	}
	
	public function actionDeliveryBoyInterval()
	{
		
		$model = DefaultSetting::find()->where(['type' =>'d_boy_interval' ])->one();
		$result = [];
		$result["status"] = "000000";
		$result["msg"] = "Success";
		$result["interval"] = $model->value;
		return $result;	
			
	}
	
	public function actionCustomerInterval()
	{
		
		$model = DefaultSetting::find()->where(['type' =>'customer_interval' ])->one();
		$result = [];
		$result["status"] = "000000";
		$result["msg"] = "Success";
		$result["interval"] = $model->value;
		return $result;	
			
	}
	
	function distance($lat1, $lon1, $lat2, $lon2, $unit) {

	  $theta = $lon1 - $lon2;
	  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	  $dist = acos($dist);
	  $dist = rad2deg($dist);
	  $miles = $dist * 60 * 1.1515;
	  $unit = strtoupper($unit);

	  if ($unit == "K") {
		  return ($miles * 1.609344);
	  } else if ($unit == "N") {
		  return ($miles * 0.8684);
	  } else {
		  return $miles;
	  }
	}
	
	/////// Tracking  delivery  boy location  saved into 
	public function actionDeliveryBoyLocation()
	{
		if(!isset($_POST['user_id']) || !isset($_POST['date']))
		{
            return [
				"status" => "000001",
				"msg" => "User id or  date  not set" 
			];
		}
		
		$user_id = $_POST['user_id'];
		$d_date = $_POST['date'];
		$date = new \DateTime($d_date);
		$date = $date->format('Y-m-d');
		$now = new \DateTime();
		$now = $now->format('Y-m-d');
		if($date != $now)
		{
			return [
				"status" => "000001",
				"msg" => "current date not set or current date not match to given date." 
			];
		}
		$result = [];
		
		$user = Users::find()->where(['id' => $user_id])->one();
		$dboy_id = isset($user->delivery_boy_id) ? $user->delivery_boy_id : '';
		$model = TrackDeliveryBoy::find()->where(['delivery_boy_id' => $dboy_id])->andWhere(['between','date_time', $now.' 00:00:00',$now.' 23:59:59'])->one();
		if(!isset($model))
		{
			return [
				"status" => "000001",
				"msg" => "Delivery not started yet. Try after sometime..." 
			];
		}
		
		$result['position'] = $model->position ;
		$result['status'] = "000000" ;
		$result['msg'] = "successfully got location" ;
		return 	$result;		
	}
	
	public function actionPendingCustomerList()
	{
		$result = [];
        $report= [];
		$out = [];
        if(!isset($_POST['dboy_id']))
		{
            return [
				"status" => "000001",
				"msg" => "Delivery boy id or product id not set" 
			];
		}
		
		$dboy_id = $_POST['dboy_id'];
		$start_date = date("Y-m-d",strtotime("first day of last month")); //date("Y-m-d",strtotime("-31 day",strtotime(date("Y-m-d"))));
		$end_date = date("Y-m-d");
		
		$subsc_model = Subscription::find()->select('user_id')->where(['<=', 'start_date', $end_date])->andWhere(['>=', 'end_date', $start_date])->andWhere(['type'=>'Daily'])->orderBy('user_id desc')->distinct()->All();
		
		if(isset($subsc_model) && ($subsc_model != null))
		{
			foreach ($subsc_model as $row) 
			{
				
				if($row->users->delivery_boy_id == $dboy_id)
				{
					$acc_mod = new AccountStatement();
					$new = $acc_mod->getPendingBillAmountReport($row->user_id,$dboy_id,1);	
					if(isset($new) && !empty($new))
					{
						array_push($report,$new);
					}
					//;
				}
			}

		}
		else{
			
			$result["status"] = "000001";
			$result["msg"] = "no customer found for pending amount";
			$result["customer_list"] = isset($report) ? $report : null;
			return $result;
			
		} 
				
		$result["status"] = "000000";
		$result["msg"] = "Success";
		$result["customer_list"] = isset($report) ? $report : null;
		return $result;	
	}
	 
}
