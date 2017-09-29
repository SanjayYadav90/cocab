<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "route".
 *
 * @property integer $id
 * @property string $route_name
 * @property string $start_position
 * @property string $end_position
 * @property integer $status
 * @property integer $delivery_boy_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $deliveryBoy
 * @property RouteMap[] $routeMaps
 */
class Route extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'route';
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
            [['route_name', 'status', 'delivery_boy_id'], 'required'],
            [['status', 'delivery_boy_id', 'created_at', 'updated_at'], 'integer'],
            [['route_name'], 'string', 'max' => 100],
            [['start_position', 'end_position'], 'string', 'max' => 50],
            [['route_name'], 'unique'],
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
            'route_name' => 'Route Name',
            'start_position' => 'Start Position',
            'end_position' => 'End Position',
            'status' => 'Status',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRouteMaps()
    {
        return $this->hasMany(RouteMap::className(), ['route_id' => 'id']);
    }
	
	public function getRoute()
    {
		 return $this->hasMany(Users::className(), ['route_id' => 'id']);
    }
	
}
