<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "paytm_transaction".
 *
 * @property int $id
 * @property string $txn_response
 * @property string $verify_response
 * @property int $created_at
 * @property int $updated_at
 */
class PaytmTransaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'paytm_transaction';
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
            [['txn_response', 'verify_response'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'txn_response' => 'Txn Response',
            'verify_response' => 'Verify Response',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
