<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "route_map".
 *
 * @property integer $id
 * @property integer $route_id
 * @property integer $user_id
 * @property double $sequence
  * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 * @property Route $route
 */
class RouteMap extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $mobile;
    public static function tableName()
    {
        return 'route_map';
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
            [['route_id', 'user_id','status'], 'required'],
            [['route_id', 'user_id','status' ,'created_at', 'updated_at'], 'integer'],
			[['mobile' ], 'safe'],
			[['sequence' ], 'number'],
            [['route_id', 'sequence'], 'unique', 'targetAttribute' => ['route_id', 'sequence'], 'message' => 'The combination of Route and Sequence has already been taken.'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['route_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::className(), 'targetAttribute' => ['route_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'route_id' => 'Route Name',
            'user_id' => 'Customer Name',
            'sequence' => 'Sequence',
			'status' => 'Status',
			'mobile' => 'User Mobile',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoute()
    {
        return $this->hasOne(Route::className(), ['id' => 'route_id']);
    }
	
	
	
	
}
