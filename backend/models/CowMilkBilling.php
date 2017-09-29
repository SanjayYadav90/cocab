<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use backend\models\Staff;
use backend\models\Users;
/**
 * This is the model class for table "cow_milk_billing".
 *
 * @property integer $id
 * @property integer $subscription_id
 * @property integer $user_id
 * @property string $bill_cycle
 * @property string $start_date
 * @property string $end_date
 * @property integer $delivered_quantity
 * @property double $sub_total
 * @property double $referral_discount
 * @property double $voucher_discount
 * @property double $tax
 * @property double $bill_amount
 * @property double $previous_due_amount
 * @property double $net_payable_amount
 * @property string $billing_gen_date
 * @property integer $delivery_boy_id
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 */
class CowMilkBilling extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $mobile;
	public $address;
    public static function tableName()
    {
        return 'cow_milk_billing';
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
            [['subscription_id', 'user_id','mobile', 'delivery_boy_id', 'delivered_quantity', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [[ 'bill_cycle', 'user_id', 'delivered_quantity', 'bill_amount', 'net_payable_amount', 'billing_gen_date'], 'required'],
            [['bill_cycle','start_date','end_date', 'billing_gen_date'], 'safe'],
            [['sub_total', 'referral_discount', 'voucher_discount', 'tax', 'bill_amount', 'previous_due_amount', 'net_payable_amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Bill No',
            'subscription_id' => 'Subscription ID',
            'user_id' => 'User Name',
            'bill_cycle' => 'Bill Cycle',
            'delivered_quantity' => 'Delivered Quantity (KG)',
            'sub_total' => 'Sub Total (Rs)',
            'referral_discount' => 'Referral Discount (Rs)',
            'voucher_discount' => 'Voucher Discount (Rs)',
            'tax' => 'Tax (Rs)',
            'bill_amount' => 'Bill Amount (Rs)',
            'previous_due_amount' => 'Previous Due Amount (Rs)',
            'net_payable_amount' => 'Net Payable Amount (Rs)',
            'billing_gen_date' => 'Billing Gen Date',
			'mobile'=>'Mobile',
			'start_date'=>'Start Date',
			'end_date'=> 'End Date',
			'delivery_boy_id'=> 'Delivery Boy',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
        $user = Users::findOne($this->user_id);
		$dboy_id = isset($user->delivery_boy_id) ? $user->delivery_boy_id : null;
		$duser = Staff::find()->where(['user_id'=> $dboy_id])->One(); 
		$name = isset($duser) ? $duser->first_name .' '. $duser->last_name : '';
		return $name;
    }
}
