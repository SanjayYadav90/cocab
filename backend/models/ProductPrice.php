<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use backend\models\DefaultSetting;
/**
 * This is the model class for table "product_price".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $quantity
 * @property string $unit
 * @property double $mrp
 * @property double $offer_price
 * @property string $offer_unit
 * @property string $offer_flag
 * @property double $discounted_mrp
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Products $product
 */
class ProductPrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_price';
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
            [['product_id', 'quantity', 'mrp'], 'required'],
            [['product_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['mrp', 'offer_price','quantity', 'discounted_mrp'], 'number'],
            [['unit'], 'string', 'max' => 35],
            [['offer_unit', 'offer_flag'], 'string', 'max' => 25],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['product_id' => 'id']],
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
            'quantity' => 'Quantity',
            'unit' => 'Unit',
            'mrp' => 'MRP',
            'offer_price' => 'Offer Discount',
            'offer_unit' => 'Offer Style',
            'offer_flag' => 'Offer Status',
            'discounted_mrp' => 'Discounted MRP',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['id' => 'product_id']);
    }
	
	public function getOfferStyle() {
		
		$offer_unit = DefaultSetting::find()->where(['type'=>'offer_unit','value'=>$this->offer_unit])->one();
		return isset($offer_unit) ? $offer_unit->name : null;
		
				
	}
	public function getPriceStatus() {
		
		$price_status = DefaultSetting::find()->where(['type'=>'price','value'=>$this->status])->one();
		return isset($price_status) ? $price_status->name : null;
		
				
	}
	public function getOfferStatus() {

		$offer_flag = DefaultSetting::find()->where(['type'=>'offer_flag','value'=>$this->offer_flag])->one();
		return isset($offer_flag) ? $offer_flag->name  : null;
		
				
	}
}
