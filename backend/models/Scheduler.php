<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "scheduler".
 *
 * @property int $id
 * @property string $name
 * @property int $template_id
 * @property string $sender_list
 * @property string $frequency_type
 * @property string $Frequency_value
 * @property string $start_date
 * @property string $time
 * @property int $created_at
 * @property int $updated_at
 */
class Scheduler extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $template_body;
    public static function tableName()
    {
        return 'scheduler';
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
            [['name', 'sender_list','frequency_type','status','start_date', 'time','template_cat','sender_type','send_msg_type'], 'required','message' => '{attribute} is required'],
            [['template_id', 'created_at', 'updated_at','status','template_cat','send_msg_type','sender_type'], 'integer'],
			[['template_id'], 'required','message' => '{attribute} is required' ,
                'isEmpty' => function ($value) {
                    return empty($value);
                }
            ],
            [['start_date', 'time','next_exec_date'], 'safe'],
            [['name', 'frequency_type', 'Frequency_value'], 'string', 'max' => 60],
			[['sender_list'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'template_id' => 'Template',
			'template_cat' => 'Template Category',
			'sender_type' => 'Sender Type',
            'sender_list' => 'Sender List',
            'frequency_type' => 'Frequency Type',
            'Frequency_value' => 'Frequency Value',
            'start_date' => 'Start Date',
            'time' => 'Time',
			'next_exec_date' => 'Next Exec Date',
			'send_msg_type' => 'Send Message Type',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
