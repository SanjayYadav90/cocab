<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "state".
 *
 * @property integer $id
 * @property string $state
 * @property integer $state2country
 * @property integer $active
 * @property integer $created_at
 * @property integer $updated_at
 */
use yii\behaviors\TimestampBehavior;



class State extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'state';
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
            [['state', 'state2country'], 'required'],
            [['state2country', 'active', 'created_at', 'updated_at'], 'integer'],
            [['state'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'state' => 'State',
            'state2country' => 'State2country',
            'active' => 'Active',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

     /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses()
    {
        return $this->hasMany(Address::className(), ['state_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState2country()
    {
        return $this->hasOne(Country::className(), ['id' => 'state2country']);
    }
    
    public static function findStateByName($state_name,$countryId)
    {
        //echo'<pre>';print_r($state_name);echo'</pre>';
        //echo'<pre>';print_r($countryId);echo'</pre>';
        if(isset($countryId) && $countryId != 0)
        {
            $stateName = State::find()
                ->where([strtolower('state') => strtolower($state_name)])
                ->andWhere(['state2country' => $countryId ])
                ->orderBy('id')
                ->one();
            //echo'<pre>';print_r($stateName);echo'</pre>'; exit;   
            return $stateName;
        }
        else
        {
            return null;
        } 
    } 
}
