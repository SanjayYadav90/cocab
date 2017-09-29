<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use backend\models\Staff;
use backend\models\CowMilkBilling;
use backend\models\Delivery;
use backend\models\Users;
use common\models\User;
use backend\models\PaytmTransaction;
use backend\models\Subscription;
/**
 * This is the model class for table "account_statement".
 *
 * @property integer $id
 * @property string $transaction_date
 * @property string $due_date
 * @property double $amount
 * @property string $payment_mode
 * @property integer $user_id
 * @property string $payment_status
 * @property integer $subscription_id
 * @property string $bank_name
 * @property string $bank_branch
 * @property string $cheque_number
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class AccountStatement extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	 public $mobile;
	 public $address;
    public static function tableName()
    {
        return 'account_statement';
    }
	public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['payment_mode', 'user_id','amount','transaction_date'], 'required'],
            [['due_date'], 'safe'],
            [['amount'], 'number'],
            [['user_id','delivery_boy_id', 'subscription_id', 'created_at', 'updated_at', 'created_by', 'updated_by','gateway_txn_id'], 'integer'],
            [['payment_mode','type', 'payment_status'], 'string', 'max' => 20],
			[['bank_name','cheque_number','bank_branch'], 'string', 'max' => 50],
			[['order_id'], 'string', 'max' => 512],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'transaction_date' => 'Transaction Date',
            'due_date' => 'Due Date',
            'amount' => 'Amount (Rs)',
            'payment_mode' => 'Payment Mode',
            'user_id' => 'User Name',
			'mobile'=>'User Mobile',
			'delivery_boy_id' => 'Delivery Boy',
			'payment_status' => 'Payment Status',
			'type'	=> 'Payment Type',
			'bank_name' => 'Bank Name',
			'cheque_number' => 'Cheque Number',
			'bank_branch' => 'Bank Branch',
			'gateway_txn_id' => 'Gateway Txn Id',
			'order_id' => 'Order ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
	public function getUserName() {
		
		$user = Staff::find()->where(['user_id'=> $this->user_id])->One(); 
		$name = isset($user) ? $user->first_name .' '. $user->last_name : '';
		return $name;
				
	}
	public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
	
	public function getDeliveryBoy()
    {
        return $this->hasOne(Users::className(), ['id' => 'delivery_boy_id']);
    }
	
	public function getPaytmTransaction()
    {
        return $this->hasOne(PaytmTransaction::className(), ['id' => 'gateway_txn_id']);
    }
	
	public function getDeliveryBoyName() {
		
		$user = Staff::find()->where(['user_id'=> $this->delivery_boy_id])->One(); 
		$name = isset($user) ? $user->first_name .' '. $user->last_name : '';
		return $name;
				
	}
	
	public function getPendingBillAmount($user_id)
	{
			
			$accountCredit = AccountStatement::find()->select('sum(amount) as amount')->where(['user_id' => $user_id,'payment_status'=>'Received','type'=>'Cr.'])->groupby('user_id')->one();
			
			if(isset($accountCredit))
			{
				$account_credit = isset($accountCredit->amount) ? $accountCredit->amount :0;
			}
			$account_debit = AccountStatement::find()->select('sum(amount) as amount')->where(['user_id' => $user_id,'payment_status'=>'Received','type'=>'Dr.'])->groupby('user_id')->one();
			if(isset($account_debit))
			{
				$cash_debit = isset($account_debit->amount) ? $account_debit->amount :0;
			}
			$tot_amount = 0;
			$net_payable_amount = 0;
			$bills = CowMilkBilling::find()->where(['user_id' => $user_id])->orderBy('bill_cycle desc')->limit(1)->One();
			if(isset($bills) && ($bills != null))
			{
				$start_date = date("Y-m-d",strtotime('+1 day',strtotime($bills->end_date)));
				$net_payable_amount = $bills->net_payable_amount;
			}
			else{
				$start_date = date("Y-m-d",strtotime('+1 day',strtotime('2017-07-31')));
			}
			$end_date = date("Y-m-d");
			$delivery = Delivery::find()->where(['user_id' => $user_id,'product_id' => 1,'isdeliver'=>2])->andWhere(['between', 'delivery_date', $start_date,$end_date])->andWhere(['<>','quantity', -1])->All();
			if(isset($delivery) && ($delivery != null))
			{
				foreach ($delivery as $drow) 
				{
					$tot_amount = $tot_amount + ($drow['delivered'] * ($drow['mrp']- $drow['area_discount']));
				}		
			}
			$total_payable = ($net_payable_amount + (isset($cash_debit) ? $cash_debit: 0)) - (isset($cash_credit) ? $cash_credit: 0);
			$pending_amount = $total_payable + $tot_amount;
			$out['pending_amount'] = $pending_amount;
			$out['bill_amount'] = $net_payable_amount;
			return $out;
	}
	
	public function getPendingBillAmountReport($user_id,$d_boy,$pending_cust=0) // third parameter is for delivery boy app to get customerlist who have pending amt.
	{
			
			$bills = CowMilkBilling::find()->where(['user_id' => $user_id])->orderBy('bill_cycle desc')->limit(1)->One();
			
			if(isset($bills) && ($bills != null))
			{
				$start_date = date("Y-m-d",strtotime('+1 day',strtotime($bills->end_date)));
				$net_payable_amount = $bills->net_payable_amount;
				$bill_gen_date = $start_date; //date("Y-m-d",strtotime($bills->billing_gen_date)));
				
				$accountCreditReceived = AccountStatement::find()->select('sum(amount) as amount')->where(['user_id' => $user_id,'payment_status'=>'Received','type'=>'Cr.'])->andWhere(['IN','payment_mode',['Cash','Cheque']])->andWhere(['between','transaction_date',$bill_gen_date,date("Y-m-d")])->groupby('user_id')->one();
				if($pending_cust ==1)
				{
					$accountPendingCredit = AccountStatement::find()->select('sum(amount) as amount')->where(['user_id' => $user_id,'payment_status'=>'Pending','type'=>'Cr.'])->andWhere(['between','transaction_date',$bill_gen_date,date("Y-m-d")])->groupby('user_id')->one();
				
				}
				$accountCredit = AccountStatement::find()->select('sum(amount) as amount')->where(['user_id' => $user_id,'payment_status'=>'Received','type'=>'Cr.'])->andWhere(['between','transaction_date',$bill_gen_date,date("Y-m-d")])->groupby('user_id')->one();
				$account_debit = AccountStatement::find()->select('sum(amount) as amount')->where(['user_id' => $user_id,'payment_status'=>'Received','type'=>'Dr.'])->andWhere(['between','transaction_date',$bill_gen_date,date("Y-m-d")])->groupby('user_id')->one();
			
				$last_bill_amount = $bills->bill_amount;
				$previous_due_amount = $bills->previous_due_amount;
			}
			else{
				$net_payable_amount = 0;
				$start_date = date("Y-m-d",strtotime('+1 day',strtotime('2017-07-31')));
				
				if($pending_cust ==1)
				{
					$accountPendingCredit = AccountStatement::find()->select('sum(amount) as amount')->where(['user_id' => $user_id,'payment_status'=>'Pending','type'=>'Cr.'])->groupby('user_id')->one();
				
				}
				$accountCredit = AccountStatement::find()->select('sum(amount) as amount')->where(['user_id' => $user_id,'payment_status'=>'Received','type'=>'Cr.'])->groupby('user_id')->one();
				$account_debit = AccountStatement::find()->select('sum(amount) as amount')->where(['user_id' => $user_id,'payment_status'=>'Received','type'=>'Dr.'])->groupby('user_id')->one();
				$accountCreditReceived = AccountStatement::find()->select('sum(amount) as amount')->where(['user_id' => $user_id,'payment_status'=>'Received','type'=>'Cr.'])->andWhere(['IN','payment_mode',['Cash','Cheque']])->groupby('user_id')->one();
			
				$last_bill_amount = 0;
				$previous_due_amount = 0;
			}
			$end_date = date("Y-m-d");
			
			$cash_credit = isset($accountCredit->amount) ? $accountCredit->amount :0;
			
			$pending_credit = isset($accountPendingCredit->amount) ? $accountPendingCredit->amount :0;
		
			$cash_debit = isset($account_debit->amount) ? $account_debit->amount :0;

			$cash_credit_rec = isset($accountCreditReceived->amount) ? $accountCreditReceived->amount :0;
			
			
			$pending_payable =  ($net_payable_amount +(isset($cash_debit) ? $cash_debit: 0)) - (isset($cash_credit) ? $cash_credit: 0);
			
			
			$delivery = Delivery::find()->where(['user_id' => $user_id,'product_id' => 1,'isdeliver'=>2])->andWhere(['between', 'delivery_date', $start_date,$end_date])->andWhere(['<>','quantity', -1])->All();
			
			$curr_tot_amount = 0;
			//$delivery_boy_id = Null;
			if(isset($delivery) && ($delivery != null))
			{
				foreach ($delivery as $drow) 
				{
					$curr_tot_amount = $curr_tot_amount + ($drow['delivered'] * ($drow['mrp']- $drow['area_discount']));
				}		
			}
			$outstanding_amount = $pending_payable + $curr_tot_amount;
			
			
			$acc_mobile = Users::findOne($user_id);
			$acc_staff = Staff::find()->where(['user_id'=>$user_id])->one();
			$acc_address = isset($acc_staff) ? $acc_staff->displayAddress :'';
			$del_staff = Staff::find()->where(['user_id'=>$acc_mobile->delivery_boy_id])->one();
			$del_boy_id = isset($del_staff) ? $del_staff->staff :'';
			
			if($pending_cust == 1  )
			{
				$pending_out = [];
				$totalPending = $pending_payable - $pending_credit;
				//$pending_out['pending_amount'] =	$totalPending;
				if($totalPending > 0){
					$pending_out['pending_customer'] =  "Yes";
					$pending_out['pending_amount'] =	$totalPending;
				}
				else{
					$pending_out['pending_customer'] =  "No";
					$pending_out['pending_amount'] =	0;
				}
				  return $pending_out;
			}
			else{
				$out['curr_mon_pending_amount'] = $curr_tot_amount;  //current month pending amount
				$out['pending_bill_amount'] = $pending_payable;
				$out['last_billed_amount'] = $last_bill_amount;
				$out['received_payment'] = $cash_credit_rec;
				$out['previous_due_amount'] = $previous_due_amount;
				$out['mobile'] = $acc_mobile->username;
				$out['address'] = $acc_address;
				$out['user_id'] = $acc_mobile->userName;
				$out['delivery_boy_id'] = $del_boy_id;
				$out['outstanding_amount'] = $outstanding_amount;  /// outstanding till today
				$out['net_payable_amount'] = $net_payable_amount;
				return $out;
			}
	}
	
	public function updateAccountStatement($user_id,$order_id,$amount,$response,$create = 0)
	{
		$response_out = json_decode($response,true);
		if($create == 1)
		{
			$model = new AccountStatement();
			$paytm_model = new PaytmTransaction();
			$paytm_model->txn_response = $response;
			$paytm_model->save();
			$model->gateway_txn_id = $paytm_model->id;
			$model->payment_status = 'Pending';
			$model->transaction_date = isset($response_out['TXNDATE']) ? date("Y-m-d",strtotime($response_out['TXNDATE'])) : date("Y-m-d");
			$model->amount = $response_out['TXNAMOUNT'];
			$model->payment_mode = 'Online';
			$model->user_id = $user_id;
			$model->type = 'Cr.';
			$model->order_id = $order_id;
			
		}
		else{
			$model = AccountStatement::find()->where(['order_id'=>$order_id])->one();
			$paytm_model = PaytmTransaction::findOne($model->gateway_txn_id);
			$paytm_model->verify_response = $response;
			$paytm_model->save();
			if($response_out['STATUS'] == 'TXN_SUCCESS')
			{
				$model->payment_status = 'Received';
			}
			else{
				$model->payment_status = 'Failed';
			}
		}
		
		if($model->save())
		{
			if($response_out['STATUS'] == 'TXN_SUCCESS')
			{
				$result["status"] = "000000";
				$result["msg"] = "Success";
				$result["order_id"] = $order_id;
				$message_text = "Dear Customer, We have received online payment of Rs. ".$model->amount." . Thank you . doodhvale.com";
				User::sendSms($model->user->username,$message_text);
				$sub_models = new Subscription();
				$sub_models->unsubscribeFromDeliveryboy($model->user_id);
			}
			else{
				$result["status"] = "000001";
				$result["order_id"] = $order_id;
				$result["msg"] = $response_out['RESPMSG'];
			}
		}
		else{
			$result["status"] = "000001";
			$result["order_id"] = $order_id;
			$result["msg"] = "Error";
		}
		
		return $result;	
		
	}
}
