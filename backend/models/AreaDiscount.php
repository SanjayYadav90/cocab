<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "area_discount".
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
class AreaDiscount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'area_discount';
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
            [['area_name','area_discount'], 'required'],
            [['area_discount', 'created_at', 'updated_at'], 'integer'],
            [['area_name'], 'string', 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'area_name' => 'Area Name',
			'area_discount' => 'Area Discount',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}
