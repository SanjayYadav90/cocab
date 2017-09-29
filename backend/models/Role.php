<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "role".
 *
 * @property integer $id
 * @property string $role_name
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property XrefUserRole[] $xrefUserRoles
 */
use yii\behaviors\TimestampBehavior;



class Role extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'role';
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
            [['role_name'], 'required'],
            [['created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['role_name'], 'string', 'max' => 50],
            [['role_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_name' => 'Role Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getXrefUserRoles()
    {
        return $this->hasMany(XrefUserRole::className(), ['role_id' => 'id']);
    }

    static public function getRole($role_name)
    {
        $role_obj = static::findOne(['role_name' => $role_name]);
        return $role_obj['id'];
    }
	
	static public function getRoleById($role_id)
    {
        $role_obj = static::findOne(['id' => $role_id]);
        return $role_obj['role_name'];
    }
}
