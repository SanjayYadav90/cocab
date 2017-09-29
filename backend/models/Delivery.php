<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior; 
use backend\models\XrefPauseSubscription;
use backend\models\Products;
use common\models\User;
use backend\models\Users;
use backend\models\Subscription;
use backend\models\DefaultSetting;
use backend\models\ProductPrice;
use backend\models\AreaDiscount;

/**
 * This is the model class for table "delivery".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $subscription_id
 * @property integer $user_id
 * @property string $delivery_date
 * @property string $delivery_time
 * @property integer $address_id
 * @property double $quantity
 * @property double $delivered
 * @property string $area
 * @property integer $delivery_boy_id
 * @property integer $isdeliver
 * @property integer $area_discount
 * @property string $unit
 * @property double $mrp
 * @property double $offer_price
 * @property string $offer_unit
 * @property string $offer_flag
 * @property double $discounted_mrp
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer empty_bottle
 * @property integer broken_bottle
 * @property integer pending_bottle
 * @property Products $product
 * @property Address $address
 * @property User $deliveryBoy
 * @property User $user
 * @property integer $unsettled
  * @property integer $pause
 */
class Delivery extends \yii\db\ActiveRecord
{
    
	public $mobile;
	public $route_name;
	public $distance;
	public $amount;
	
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'delivery';
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
            [['product_id', 'user_id','subscription_id','quantity'], 'required'],
            [['product_id','pause','unsettled','subscription_id', 'user_id', 'address_id','area_discount','empty_bottle','broken_bottle','pending_bottle', 'delivery_boy_id', 'isdeliver', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['delivery_date','mobile','delivery_time','route_name','distance','amount'], 'safe'],
            [['quantity','delivered','mrp', 'offer_price', 'discounted_mrp'], 'number'],
            [['area'], 'string', 'max' => 50],
			[['unit'], 'string', 'max' => 35],
			[['offer_unit', 'offer_flag'], 'string', 'max' => 25],
            /* [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['address_id'], 'exist', 'skipOnError' => true, 'targetClass' => Address::className(), 'targetAttribute' => ['address_id' => 'id']],
            [['delivery_boy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['delivery_boy_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']], */
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product',
			'subscription_id' => 'subscription_id',
            'user_id' => 'User',
            'delivery_date' => 'Delivery Date',
			'delivery_time' => 'Delivered Date Time',
            'address_id' => 'Address',
            'quantity' => 'Order',
			'delivered' => 'Delivered',
            'area' => 'Area',
			'mobile' => 'Mobile',
            'delivery_boy_id' => 'Delivery Boy ',
            'isdeliver' => 'Order Status',
			'area_discount' =>'Area Discount',
			'unit' => 'Unit',
            'mrp' => 'MRP',
            'offer_price' => 'Offer Price',
            'offer_unit' => 'Offer Unit',
            'offer_flag' => 'Offer Status',
			'empty_bottle' => 'Empty Bottle',
			'broken_bottle' => 'Broken Bottle',
			'pending_bottle'=> 'Pending Bottle',
            'discounted_mrp' => 'Discounted MRP',
			'amount' => 'Amount',
			'unsettled' => 'Unsettled?',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['id' => 'product_id']);
    }
	
	public function getSubscription()
    {
        return $this->hasOne(Subscription::className(), ['id' => 'subscription_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(Address::className(), ['id' => 'address_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryBoy()
    {
        return $this->hasOne(Users::className(), ['id' => 'delivery_boy_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
	
	public function getDeliveryBoyFullName()
	{
		$boy = $this->deliveryBoy;
		 
		return $boy->staff->first_name.' '.$boy->staff->last_name;
	}
	
	public function getRoutemap()
	{
		return $this->hasOne(RouteMap::className(),['user_id' => 'user_id']);
	}
	public function getRoute()
	{
		return $this->hasOne(Route::className(),['delivery_boy_id' => 'delivery_boy_id']);
	}
	
	public function getAnalytic()
	{
		return $this->hasOne(DeliveryAnalytics::className(),['delivery_boy_id' => 'delivery_boy_id']);
	}
	
	public function loadOrders()
	{
		$no_days = DefaultSetting::getConfigByName('order_load_days');		
		$start_date = date('Y-m-d');
		$end_date = date('Y-m-d',strtotime('+'.$no_days. 'day',strtotime(date('Y-m-d'))));
		
		$subscriptions = Subscription::find()->where(['<=', 'start_date', $end_date])->andWhere(['>=', 'end_date', $start_date])->andWhere(['<>', 'status', 0])->asArray()->All();
		
		//print_r($subscription);
		if(isset($subscriptions )){
			
			foreach ($subscriptions as $subs_model)
			{
				$date = $start_date;
				$i = 1;
				while($no_days >= $i)
				{
					$quantity = $subs_model['quantity'];
					$unsettled = 0;
					$pause = 0;
					$discount = AreaDiscount::findOne($subs_model['area_discount']);
					if(isset($discount) && !empty($discount)){
						$area_discount = $discount->area_discount;
					}
					else{
						$area_discount = 0;
					}
					//echo "sub_id: ".$subs_model['id'] ;echo", " ; echo  $area_discount;echo"<br/>";
					//$area_discount = !empty($subs_model['area_discount'])? $subs_model['area_discount'] : 0;
					
					if($subs_model['type'] == 'Daily'){
						if($subs_model['status'] == 2){
							$quantity = 0;
							$unsettled = 1;
						}
						else{
							$pause_items = XrefPauseSubscription::find()->where(['subscription_id' => $subs_model['id'],'start_date' => $date])->One();
							if(isset($pause_items))
							{
								if($pause_items['type'] == 'edit')
								{
									$quantity = $pause_items['quantity'];
								}
								else{
									$quantity = 0;
									$pause = 1;
								}
							}
						}
						
						$delivery_item = Delivery::find()->where(['subscription_id' => $subs_model['id'],'delivery_date' => $date])->One();
						if($delivery_item){
							$delivery_boy = Users::findOne($delivery_item->user_id);
							
							$delivery_item->quantity = $quantity;
							$delivery_item->delivery_boy_id = $delivery_boy->delivery_boy_id;
							$address_id = isset($delivery_boy->staff) ? $delivery_boy->staff->address_id : '';
							$delivery_item->address_id = $address_id;
							$productprice = ProductPrice::find()->where(['status'=> 1,'product_id'=>$delivery_item->product_id])->one();
							if(isset($productprice) && !empty($productprice))
							{
								$delivery_item->mrp = $productprice->mrp;
								$delivery_item->unit = $productprice->unit;
							}
							else{
								$product = Products::findOne($delivery_item->product_id);
								$delivery_item->mrp = $product->price;
							}
							$delivery_item->area_discount = $area_discount;
							$delivery_item->unsettled = $unsettled ;
							$delivery_item->pause = $pause ;
							$delivery_item->save();
						}
						else{
							$model = new Delivery();
							$delivery_boy = Users::findOne($subs_model['user_id']);
							$address_id = isset($delivery_boy->staff) ? $delivery_boy->staff->address_id : '';
							$model->subscription_id = $subs_model['id'];
							$model->product_id = $subs_model['product_id'];
							$model->area_discount = $area_discount;
							$model->user_id = $subs_model['user_id'];
							$model->quantity = $quantity;
							$model->delivery_date = $date;
							$model->address_id = $address_id;
							$model->isdeliver = 1;
							$model->unsettled = $unsettled ;
							$model->pause = $pause ;
							$model->delivery_boy_id = $delivery_boy->delivery_boy_id;
							$productprice = ProductPrice::find()->where(['status'=> 1,'product_id'=>$subs_model['product_id']])->one();
							if(isset($productprice))
							{
								$model->mrp = $productprice->mrp;
								$model->unit = $productprice->unit;
							}
							else{
								$product = Products::findOne($model->product_id);
								$model->mrp = $product->price;
							}
							$model->save();
						}
						
					}
					else{
						
						$pause_items = XrefPauseSubscription::find()->where(['subscription_id' => $subs_model['id'],'start_date' => $date])->One();
						
						if(isset($pause_items))
						{
							if($pause_items['type'] == 'edit')
							{
								$quantity = $pause_items['quantity'];
							}
							else{
								$quantity = 0;
							}
						}
						
						$delivery_item = Delivery::find()->where(['subscription_id' => $subs_model['id'],'delivery_date' => $date])->One();
						if($delivery_item){
							$delivery_boy = Users::findOne($delivery_item->user_id);
							$address_id = isset($delivery_boy->staff) ? $delivery_boy->staff->address_id : '';
							$delivery_item->address_id = $address_id;
							$delivery_item->quantity = $quantity;
							$delivery_item->delivery_boy_id = $delivery_boy->delivery_boy_id;
							$productprice = ProductPrice::find()->where(['status'=> 1,'product_id'=>$subs_model['product_id']])->one();
							if(isset($productprice))
							{
								$delivery_item->mrp = $productprice->mrp;
								$delivery_item->unit = $productprice->unit;
							}
							else{
								$product = Products::findOne($delivery_item->product_id);
								$delivery_item->mrp = $product->price;
							}
							$delivery_item->area_discount = $area_discount;
							$delivery_item->save();
							break;
						}
						else{
							$model = new Delivery();
							
							$delivery_boy = Users::findOne($subs_model['user_id']);
							$address_id = isset($delivery_boy->staff) ? $delivery_boy->staff->address_id : '';
							$model->subscription_id = $subs_model['id'];
							$model->product_id = $subs_model['product_id'];
							$model->user_id = $subs_model['user_id'];
							$model->quantity = $quantity;
							$model->delivery_date = $date;
							$model->area_discount = $area_discount;
							$model->address_id = $address_id;
							$model->isdeliver = 1;
							$model->delivery_boy_id = $delivery_boy->delivery_boy_id;
							$productprice = ProductPrice::find()->where(['status'=> 1,'product_id'=>$subs_model['product_id']])->one();
							if(isset($productprice))
							{
								$model->mrp = $productprice->mrp;
								$model->unit = $productprice->unit;
							}
							else{
								$product = Products::findOne($model->product_id);
								$model->mrp = $product->price;
							}
							$model->save();
							break;
						}
					}
					$date = date('Y-m-d', strtotime($date .' +1 day'));
					$i++;
				}
				
			}
		}
		//$date_start = date('Y-m-d',strtotime('+1 day',strtotime(date('Y-m-d'))));
		$subscription_del = new Subscription();
		$subscription_del->deleteExtraDelivery();
		return true;
	}
	
}
