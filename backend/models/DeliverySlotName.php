<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "delivery_slot_name".
 *
 * @property integer $id
 * @property string $delivery_slot
 * @property double $delivery_charge
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class DeliverySlotName extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'delivery_slot_name';
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
            [['delivery_slot','status'], 'required'],
            [['delivery_charge'], 'number'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['delivery_slot'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'delivery_slot' => 'Delivery Slot',
            'delivery_charge' => 'Delivery Charge',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
