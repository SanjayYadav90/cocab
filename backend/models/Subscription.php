<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use backend\models\Products;
use backend\models\Staff;
use backend\models\Address;
use backend\models\Delivery;

/**
 * This is the model class for table "subscription".
 *
 * @property integer $id
 * @property string $start_date
 * @property string $end_date
 * @property integer $quantity
 * @property string  $type
 * @property integer $product_id
 * @property integer $user_id
 * @property integer $area_discount
 * @property string $unit
 * @property double $mrp
 * @property double $offer_price
 * @property string $offer_unit
 * @property string $offer_flag
 * @property double $discounted_mrp
 * @property integer $coupon_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property Users $users
 * @property Product $product
 * @property XrefPauseSubscription[] $xrefPauseSubscriptions
 */




class Subscription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $address;
	public $mobile;
    public static function tableName()
    {
        return 'subscription';
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
            [['start_date', 'quantity', 'product_id', 'user_id','status'], 'required'],
            [['start_date', 'end_date','address','mobile' ], 'safe'],
            [['type'], 'string', 'max' => 50],
			[['mrp','offer_price', 'discounted_mrp'], 'number'],
			[['unit'], 'string', 'max' => 35],
			[['offer_unit', 'offer_flag'], 'string', 'max' => 25],
            [['quantity', 'product_id', 'user_id', 'area_discount', 'coupon_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
		];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'quantity' => 'Quantity',
            'type' => 'Type',
            'product_id' => 'Product',
            'user_id' => 'User',
			'address' => 'Address',
			'mobile' => 'Mobile',
			'status'=> 'Status',
			'area_discount' =>'Area Discount',
			'unit' => 'Unit',
            'mrp' => 'MRP',
            'offer_price' => 'Offer Price',
            'offer_unit' => 'Offer Unit',
            'offer_flag' => 'Offer Status',
            'discounted_mrp' => 'Discounted MRP',
            'coupon_id' => 'Coupon ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
	
	public function getProductName() {
		
		$product = Products::findOne($this->product_id); 
		return $product->name;
				
	}
	public function getUserName() {
		
		$user = Staff::find()->where(['user_id'=> $this->user_id])->One(); 
		//print_r($user);exit;
		$name = $user->first_name .' '. $user->last_name;
		return $name;
				
	}
	
	public function getFullAddress() {
		
		$add = 'address not found';
		$user = $this->users;
		$user_details = Staff::find()->where(['user_id'=> $user->id])->one(); 
		if(isset($user_details))
		{
			$address = Address::find()->where(['id'=> $user_details->address_id])->one(); 
			$add = $address->address1 .' '. $address->address2 .' '. $address->city .' '. $address->pincode; 
			return $add; 
		}
		else return $add;		
				
	}
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
	
	public function getProduct()
    {
        return $this->hasOne(Products::className(), ['id' => 'product_id']);
    }
	
	public function getDiscount()
    {
        return $this->hasOne(AreaDiscount::className(), ['id' => 'area_discount']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXrefPauseSubscriptions()
    {
        return $this->hasMany(XrefPauseSubscription::className(), ['subscription_id' => 'id']);
    }
	
	public static function findSubscription($product,$user_id)
    {
        
		return $subscription = Subscription::find()->where(['user_id'=> $user_id,'product_id'=>$product])->andWhere(['IN', 'status',[1,2]])->one();
		
    }
	
	
	/// function for change the subscription status 
	public function unsubscribeFromDeliveryboy($user_id)
	{
		$sub_model = Subscription::find()->where(['user_id'=> $user_id,'product_id'=>1])->andWhere(['<>', 'status',0])->one();
		if(isset($sub_model) && $sub_model->status == 2) {
			$acc_model = new AccountStatement();
			$account_details = $acc_model->getPendingBillAmountReport($user_id,0);
			$pending_amount = $account_details['outstanding_amount'];
			if($pending_amount <= 0)
			{
				$sub_model->status = 0;   // unsubscribed from unsettled subscription
				$sub_model->end_date = date("Y-m-d");
				$sub_model->save();
				$sub_model->deleteExtraDelivery();
			}
		}
		return ;
	}
	
	public function deleteExtraDelivery()
	{
		$date_start = date('Y-m-d');
		$date_end = date('Y-m-d',strtotime('+3 day',strtotime(date('Y-m-d'))));
		$unsubscriptions = Subscription::find()->where(['between', 'end_date', $date_start,$date_end])->andWhere(['=', 'status', 0])->asArray()->All();
		if(isset($unsubscriptions )){
			foreach ($unsubscriptions as $row)
			{
				$delivery_row = Delivery::find()->where(['subscription_id' => $row['id'],'delivery_date' => $row['end_date'],'user_id'=>$row['user_id']])->One();
				if(isset($delivery_row))
				{
					$delivery_row->delete();
				}
			}

		}
		return;
	}
}
