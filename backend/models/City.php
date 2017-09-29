<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "city".
 *
 * @property integer $id
 * @property string $city
 * @property integer $city2state
 * @property integer $active
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property State $city2state0
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'city';
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
            [['city', 'city2state'], 'required'],
            [['city2state', 'active', 'created_at', 'updated_at'], 'integer'],
            [['city'], 'string', 'max' => 255],
            [['city2state'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['city2state' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city' => 'City',
            'city2state' => 'State',
            'active' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity2state0()
    {
        return $this->hasOne(State::className(), ['id' => 'city2state']);
    }
}
