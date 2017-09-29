<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "track_delivery_boy".
 *
 * @property integer $id
 * @property string $date_time
 * @property string $position
 * @property integer $delivery_boy_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $deliveryBoy
 */
class TrackDeliveryBoy extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'track_delivery_boy';
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
            [['date_time','delivery_boy_id'], 'required'],
            [['date_time'], 'safe'],
            [['delivery_boy_id', 'created_at', 'updated_at'], 'integer'],
            [['position'], 'string', 'max' => 512],
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
            'date_time' => 'Date Time',
            'position' => 'Position',
            'delivery_boy_id' => 'Delivery Boy ',
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
