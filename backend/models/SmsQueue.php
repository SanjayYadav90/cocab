<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "sms_queue".
 *
 * @property int $id
 * @property string $message_text
 * @property int $type
 * @property int $to_phone
 * @property int $status
 * @property string $date_sent
 * @property int $user_id
 * @property int $created_at
 * @property int $updated_at
 */
class SmsQueue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms_queue';
    }

	/**
     * @inheritdoc
     */
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
            [['message_text', 'to_phone', 'status', 'user_id'], 'required'],
            [['type', 'to_phone', 'status', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['date_sent'], 'safe'],
            [['message_text'], 'string', 'max' => 1024],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'message_text' => 'Message Text',
            'type' => 'Type',
            'to_phone' => 'To Phone',
            'status' => 'Status',
            'date_sent' => 'Date Sent',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
