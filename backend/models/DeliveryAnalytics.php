<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "delivery_analytics".
 *
 * @property integer $id
 * @property double $distance
 * @property double $total_time
 * @property integer $delivery_boy_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $travel_date
 * @property string $unit
 * @property string $last_location
 *
 * @property User $deliveryBoy
 */
class DeliveryAnalytics extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'delivery_analytics';
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
            [['distance','delivery_boy_id','travel_date'], 'required'],
            [['distance', 'total_time'], 'number'],
			[['travel_date'], 'safe'],
			[['unit'], 'string', 'max' => 15],
			[['last_location'], 'string', 'max' => 100],
            [['delivery_boy_id', 'created_at', 'updated_at'], 'integer'],
            [['delivery_boy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['delivery_boy_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
			'travel_date' => 'Date',
            'distance' => 'Distance',
            'total_time' => 'Total Time',
			'unit' =>'Unit',
            'delivery_boy_id' => 'Delivery Boy',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryBoy()
    {
        return $this->hasOne(Users::className(), ['id' => 'delivery_boy_id']);
    }
}
