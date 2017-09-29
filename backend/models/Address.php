<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property string $address1
 * @property string $address2
 * @property string $city
 * @property integer $state_id
 * @property integer $country_id
 * @property integer $pincode
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property Staff[] $staff
 */



class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
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
            [['address1', 'city', 'state_id', 'country_id'], 'required'],
            [['state_id', 'country_id', 'pincode', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['address1', 'address2'], 'string', 'max' => 1024],
            [['city'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address1' => 'Address1',
            'address2' => 'Area',
            'city' => 'City',
            'state_id' => 'State',
            'country_id' => 'Country',
            'pincode' => 'Pincode',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaff()
    {
        return $this->hasMany(Staff::className(), ['address_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'state_id']);
    }
}
