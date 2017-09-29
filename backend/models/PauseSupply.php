<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pause_supply".
 *
 * @property integer $id
 * @property string $start_date
 * @property string $end_date
 * @property integer $subscription_id
 * @property integer $status
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */

use yii\behaviors\TimestampBehavior;



class PauseSupply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pause_supply';
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
            [['start_date', 'subscription_id'], 'required'],
            [['start_date', 'end_date'], 'safe'],
            [['subscription_id', 'user_id','status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
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
            'status' => 'Status',
            'subscription_id' => 'Subscription ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
}
