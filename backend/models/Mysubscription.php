<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "subscription".
 *
 * @property integer $id
 * @property string $start_date
 * @property string $end_date
 * @property integer $quantity
 * @property string $type
 * @property integer $product_id
 * @property integer $status
 * @property integer $user_id
 * @property integer $coupon_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property XrefPauseSubscription[] $xrefPauseSubscriptions
 */
class Mysubscription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subscription';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['start_date', 'quantity', 'product_id', 'user_id'], 'required'],
            [['start_date', 'end_date'], 'safe'],
            [['quantity', 'product_id', 'status', 'user_id', 'coupon_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['type'], 'string', 'max' => 50],
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
            'type' => 'Type',
            'product_id' => 'Product ID',
            'status' => 'Status',
            'user_id' => 'User ID',
            'coupon_id' => 'Coupon ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXrefPauseSubscriptions()
    {
        return $this->hasMany(XrefPauseSubscription::className(), ['subscription_id' => 'id']);
    }
}
