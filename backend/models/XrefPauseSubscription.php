<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "xref_pause_subscription".
 *
 * @property integer $id
 * @property string $start_date
 * @property string $end_date
 * @property integer $quantity
 * @property integer $subscription_id
 * @property integer $status
 * @property string $type
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property Subscription $subscription
 * @property Users $users
 */
class XrefPauseSubscription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $mobile;
	public $address;
	public $product_id;
    public static function tableName()
    {
        return 'xref_pause_subscription';
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
            [['start_date', 'subscription_id', 'user_id','type','quantity'], 'required'],
            [['start_date', 'end_date','address','mobile','product_id'], 'safe'],
            [['quantity', 'subscription_id', 'status', 'user_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['type'], 'string', 'max' => 20],
            [['subscription_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subscription::className(), 'targetAttribute' => ['subscription_id' => 'id']],
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
            'subscription_id' => 'Product',
			'product_id' => 'Product',
            'status' => 'Status',
            'type' => 'Type',
			'address' => 'Address',
			'mobile' => 'Mobile',
            'user_id' => 'User Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubscription()
    {
        return $this->hasOne(Subscription::className(), ['id' => 'subscription_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
}
