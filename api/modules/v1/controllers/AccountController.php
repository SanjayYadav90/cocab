<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use common\models\User;
use backend\models\AccountStatement;
use backend\models\Delivery;
use backend\models\CowMilkBilling;
use common\models\Crypto;
use backend\models\Subscription;
define('PAYTM_MERCHANT_KEY', 'FGLMxTiqctuf!UA1');
define('MID', 'Sanjee24805258792097');
define('WEBSITE' , 'SanjeeWAP');
define('INDUSTRY_TYPE_ID','Retail109');
define('CHANNEL_ID', 'WAP');


/**
 * User Controller API

 */
class AccountController extends ActiveController
{
    public $modelClass = 'backend\models\AccountStatement';    
	
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
	
	
	
	public function actionAccountList()
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
        
		$account = AccountStatement::find()->where(['user_id' => $user_id])->asArray()->All();
		if(isset($account) && ($account != null))
		{
			foreach ($account as $row) 
			{
				$out['account_id'] = $row['id'];  
				$out['transaction_date'] = $row['transaction_date'];
				$out['amount'] = $row['amount'];
				$out['payment_mode'] = $row['payment_mode'];
				array_push($new_list,$out);
			}		
		}

		$result["status"] = "000000";
		$result["msg"] = "Success";
		$result["account_list"] = isset($new_list) ? $new_list : null;
		
		return $result;	
	}
	
	
	public function actionOrderHistory()
	{
		if(!isset($_POST['user_id']) || !isset($_POST['date_from']))
		{
            return [
				"status" => "000001",
				"msg" => "user id or start date not set" 
			];
		} 
		$user_id = $_POST['user_id'];
		if(!isset($_POST['date_to'])){
		  if (($timestamp = strtotime($_POST['date_from'])) !== false)
			{
			  $php_date = getdate($timestamp);
			  $date = date("Y-m-01", $timestamp); // see the date manual page for format options      
			}
			else
			{
			  return [
				"status" => "000001",
				"msg" => "date format not valid" 
				];
			}
		} 
		$date_from = isset($_POST['date_to'])? $_POST['date_from'] : $date;
		$date_to = isset($_POST['date_to'])? $_POST['date_to'] : $_POST['date_from'];
	
		$result = [];
		$out = [];
        $new_list= [];
        
		$delivery = Delivery::find()->where(['user_id' => $user_id,'isdeliver'])->andWhere(['between','delivery_date', $date_from, $date_to])->All();
		
		if(isset($delivery) && ($delivery != null))
		{
			foreach ($delivery as $row) 
			{
				$out['delivery_id'] = $row->id;  
				$out['delivery_date'] = $row->delivery_date;
				$out['quantity'] = $row->quantity;
				$out['product_id'] = $row->product_id;
				$out['product_name'] = $row->product->name;
				$out['delivery_boy'] = $row->deliveryBoy->staff->first_name;
				$address = $row->address;
				$full_address = $address->address1 .' '. $address->address1 .' '. $address->city;
				$out['address'] = $full_address;
				$out['area'] = $row['area'];
				array_push($new_list,$out);
			}		
		}
		else{
			return [
				"status" => "000001",
				"msg" => "You dont have any order history for this month." 
				];
		}
		$result["status"] = "000000";
		$result["msg"] = "Success";
		$result["account_list"] = isset($new_list) ? $new_list : null;
		
		return $result;	
	}
	
	public function actionAccountUpdate()
	{
		if(!isset($_POST['user_id']) || !isset($_POST['transaction_date']) || !isset($_POST['amount']) || !isset($_POST['payment_mode']))
		{
            return [
				"status" => "000001",
				"msg" => "user id or transaction date or amount or pay mode not set" 
			];
		} 
		$result = [];
		$model = new AccountStatement();
		$model->transaction_date = $_POST['transaction_date'];
		$model->amount = $_POST['amount'];
		$model->payment_mode = $_POST['payment_mode'];
		$model->user_id = $_POST['user_id'];
		$model->delivery_boy_id = isset($_POST['delivery_boy_id'])? $_POST['delivery_boy_id'] : NULL;
		$model->type = 'Cr.';
		if($_POST['payment_mode'] == 'Cheque')
		{
			$model->payment_status = 'Pending';
		}
		else{
			$model->payment_status = 'Received';
		}
		if($model->save())
		{
			$result["status"] = "000000";
			$result["msg"] = "Success";
			$result["account_list"] = $model;
			if($model->payment_mode == 'Cheque'){
				$message_text = "Dear Customer, We have received cheque of amount of Rs. ".$model->amount." . Payment will be adjusted after realization of the cheque. Thank you . ".isset($model->deliveryBoyName)?$model->deliveryBoyName:''." .doodhvale.com";
			}
			else{
				$message_text = "Dear Customer, We have received cash payment of Rs. ".$model->amount." . Thank you ".isset($model->deliveryBoyName)?$model->deliveryBoyName:''." . doodhvale.com";
			}
			
			User::sendSms($model->user->username,$message_text);
			$sub_models = new Subscription();
			$sub_models->unsubscribeFromDeliveryboy($model->user_id);
		}
		else{
			$result["status"] = "000001";
			$result["msg"] = "something went wrong to save payment! Try again!";
		}
		
		return $result;	
	}
	
	public function actionCustomerBill()
	{
		if(!isset($_POST['user_id']) /* || !isset($_POST['date_from']) */)
		{
            return [
				"status" => "000001",
				"msg" => "user id  not set" 
			];
		} 
		$user_id = $_POST['user_id'];
		$result = [];
		$out = [];
		$bill_model =[];
        $new_list= [];
		$delivery_item= [];
        $bills = CowMilkBilling::find()->where(['user_id' => $user_id])->orderBy('bill_cycle desc')->limit(6)->All();
		if(isset($bills) && ($bills != null))
		{
			
			foreach ($bills as $row) 
			{
				$bill_model['bill_no'] 	= $row->id;
				$bill_model['bill_cycle'] 	= date("M-Y" ,strtotime($row->bill_cycle));
				$bill_model['start_date'] 	= $row->start_date;
				$bill_model['end_date'] 	= $row->end_date;
				$bill_model['subscription_id'] 	= $row->subscription_id;
				$bill_model['delivered_quantity'] = $row->delivered_quantity;
				$bill_model['sub_total'] 			= $row->sub_total;
				$bill_model['referral_discount'] 	= $row->referral_discount;
				$bill_model['voucher_discount'] 	= $row->voucher_discount;
				$bill_model['tax'] 				= $row->tax;
				$bill_model['bill_amount'] 		= $row->bill_amount;
				$bill_model['previous_due_amount'] = $row->previous_due_amount;
				$bill_model['net_payable_amount']  = $row->net_payable_amount;
				$bill_model['billing_gen_date']  	= $row->billing_gen_date;
				$delivery = Delivery::find()->where(['user_id' => $row->user_id,'product_id' => 1,'subscription_id' => $row->subscription_id])->andWhere(['between', 'delivery_date', $row->start_date,$row->end_date])->andWhere(['<>','quantity', -1])->All();
				if(isset($delivery) && ($delivery != null))
				{
					foreach ($delivery as $drow) 
					{
						$out['delivery_id'] = $drow->id;  
						$out['delivery_date'] = $drow->delivery_date;
						$out['delivered'] = $drow->delivered;
						$out['mrp'] = $drow->mrp;
						$out['area_discount'] = $drow->area_discount;
						$out['product'] = $drow->product->name;
						$out['row_amount'] = $drow['delivered'] * ($drow['mrp']- $drow['area_discount']);
						
						array_push($delivery_item,$out);
					}		
				}
				$bill_model['billing_detail'] = $delivery_item;
				array_push($new_list,$bill_model);
			}		
		}
		else{
			return [
				"status" => "000001",
				"msg" => "You dont have any bill ." 
				];
		}
		
		$result["status"] = "000000";
		$result["msg"] = "Success";
		$result["bill_list"] = isset($new_list) ? $new_list : null;
		
		return $result;	
	}
	
	public function actionAccountView()
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
        
		$account = AccountStatement::find()->where(['user_id' => $user_id,'payment_status'=>'Received'])->asArray()->All();
		if(isset($account) && ($account != null))
		{
			foreach ($account as $row) 
			{ 
				$out['transaction_date'] = $row['transaction_date'];
				$out['amount'] = $row['amount'];
				$out['type'] = $row['type'];
				$out['payment_mode'] = $row['payment_mode'];
				array_push($new_list,$out);
			}		
		}
		
		$acc_model = new AccountStatement();
		$account_details = $acc_model->getPendingBillAmountReport($user_id,0);
		$result['pending_amount'] = $account_details['outstanding_amount'];  
		$result['bill_amount'] = $account_details['net_payable_amount'];

		$result["status"] = "000000";
		$result["msg"] = "Success";
		$result["account_list"] = isset($new_list) ? $new_list : null;
		
		return $result;	
	}
	public function actionGenerateChecksum()
	{
		if(!isset($_POST['CUST_ID']))
		{
            return [
				"status" => "000001",
				"msg" => "user id not set" 
			];
		} 
		$result = [];
        $checkSum = "";

		// below code snippet is mandatory, so that no one can use your checksumgeneration url for other purpose .
		$findme   = 'REFUND';
		$findmepipe = '|';

		$paramList = array();

		$paramList["MID"] = MID;
		$paramList["INDUSTRY_TYPE_ID"] = INDUSTRY_TYPE_ID;
		$paramList["CHANNEL_ID"] = CHANNEL_ID;
		$paramList["WEBSITE"] = WEBSITE;
		//$paramList["CALLBACK_URL"]="https://pguat.paytm.com/paytmchecksum/paytmCallback.jsp"; // url for staging server
		$paramList["CALLBACK_URL"]="https://securegw.paytm.in/theia/paytmCallback?ORDER_ID=".$_POST['ORDER_ID'];  //url for production server
		foreach($_POST as $key=>$value)
		{  
		  $pos = strpos($value, $findme);
		  $pospipe = strpos($value, $findmepipe);
		  if ($pos === false || $pospipe === false) 
			{
				$paramList[$key] = $value;
			}
		}
		//return $paramList;
		$cryptoModel = new Crypto;
		$checkSum = $cryptoModel->getChecksumFromArray($paramList,PAYTM_MERCHANT_KEY);
		//$checkSumResult = json_encode(array("CHECKSUMHASH" => $checkSum,"ORDER_ID" => $_POST["ORDER_ID"], "payt_STATUS" => "1"));

		$result["status"] = "000000";
		$result["msg"] = "Success";
		$result["checkSum"] = isset($checkSum) ? $checkSum : null;
		$result["post_value"] = isset($paramList) ? $paramList : null;
		
		return $result;	
	}
	
	public function actionVerifyTransaction()
	{
		if(!isset($_POST['ORDER_ID']) || !isset($_POST['CUST_ID'])|| !isset($_POST['TXNAMOUNT']) || !isset($_POST['TXNID']) || !isset($_POST['Response']) )
		{
            return [
				"status" => "000001",
				"msg" => "order id or response or amount or txnid not set" 
			];
		}  
		if(is_array($_POST['Response']))
		{
			$response = json_encode($_POST['Response']);
		}
		else{
			$response = $_POST['Response'];
		}
		//return $response;
		$response_out = json_decode($response,true);

		$model = new AccountStatement();
		
		$result = $model->updateAccountStatement($_POST['CUST_ID'],$_POST['ORDER_ID'],$_POST['TXNAMOUNT'],$response,1); // 1 stand for  insert record
		
		//$result = [];
        $checkSum = "";

		// below code snippet is mandatory, so that no one can use your checksumgeneration url for other purpose .
		$order_id = $_POST['ORDER_ID'];
		$txn_amount = $_POST['TXNAMOUNT'];
		$txn_id = $_POST['TXNID'];
		$paramList = array();
		$paramList["ORDER_ID"] = $order_id;
		$paramList["MID"] = MID;
		
		$cryptoModel = new Crypto;
		$checkSum = $cryptoModel->getChecksumFromArray($paramList,PAYTM_MERCHANT_KEY);
		//$checkSumResult = json_encode(array("CHECKSUMHASH" => $checkSum,"ORDER_ID" => $_POST["ORDER_ID"], "payt_STATUS" => "1"));
		$request=array("MID"=>MID,"ORDERID"=>$order_id,"CHECKSUMHASH"=>$checkSum);

		$JsonData =json_encode($request);
		$postData = 'JsonData='.urlencode($JsonData);
		//$url = "https://pguat.paytm.com/oltp/HANDLER_INTERNAL/getTxnStatus"; //for staging server
		$url = "https://secure.paytm.in/oltp/HANDLER_INTERNAL/getTxnStatus";   // for production server
		$HEADER[] = "Content-Type: application/json";
		$HEADER[] = "Accept: application/json";

		$args['HEADER'] = $HEADER;  
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);	
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $args['HEADER']);
		$server_output = curl_exec($ch);

		//return $server_output;
		$response = json_decode($server_output,true);
		//return $response['TXNAMOUNT'];
/* 		if(($response['TXNAMOUNT'] == $txn_amount) && ($response['TXNID'] == $txn_id))
		{ */
			$result = $model->updateAccountStatement($_POST['CUST_ID'],$_POST['ORDER_ID'],$_POST['TXNAMOUNT'],$server_output,0); // 0 stand for  update record
		/* }
		
		else{
			$result["status"] = "000001";
			$result["msg"] = "Error";
			$result["order_id"] = $order_id;
		} */
		
		return $result;	
	}
	

}
