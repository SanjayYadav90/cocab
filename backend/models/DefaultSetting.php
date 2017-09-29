<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "default_setting".
 *
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property string $subject
 * @property string $value
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class DefaultSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'default_setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type', 'value'], 'required'],
            [['created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 128],
            [['subject'], 'string', 'max' => 1024],
            [['value'], 'string', 'max' => 4096],
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
            'type' => 'Type',
            'subject' => 'Subject',
            'value' => 'Value',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

        
    static public function getConfigByName($name)
    {
        $value = "";
        $recConfig = DefaultSetting::findOne(['name' => $name]);
        
        return $recConfig['value'];
    }
	
	static public function getConfigByValue($value,$type)
    {
        $name = "";
        $recConfig = DefaultSetting::findOne(['value' => $value, 'type' => $type]);
        
        return $recConfig['name'];
    }
        
}
