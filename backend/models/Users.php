<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $role
 * @property string $otp
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Staff[] $staff

 */
use yii\behaviors\TimestampBehavior;



class Users extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
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
            [['username','mobile','role'], 'required'],
            [['status', 'role', 'mobile','distributor_id','delivery_boy_id','route_id','first_login','created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['otp'], 'string', 'max' => 100],
            [['username'], 'unique' ,'message' => 'This mobile number has already been registered.','on'=>'staff'],
			//[['username'], 'string', 'max' => 10,'on'=>'staff'],
			[['mobile'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
			'mobile' =>'Mobile',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
			'delivery_boy_id'=>'Delivery Boy',
			'distributor_id'=>'Distributor',
			'route_id' => 'Route Name',
			'role' => 'Role',
            'otp' => 'Otp',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
	
	/**
     * @inheritdoc
     */
/* 	 public function scenarios()
	{
		//$scenarios = parent::scenarios();
		//$scenarios['vehicle'] = ['username','status'];//Scenario Values Only Accepted
        //$scenarios['user_pwd'] = ['confirmpassword','newPassword'];//Scenario Values Only Accepted
        //$scenarios['user'] = ['first_name','last_name','middle_name','email'];//Scenario Values Only Accepted
        //$scenarios['staff'] = ['username','status','mobile','role'];//Scenario Values Only Accepted
		//$scenarios['parent'] = ['status'];//Scenario Values Only Accepted
		return $scenarios;
	}  */

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaff()
    {
        //return $this->hasMany(Staff::className(), ['user_id' => 'id']);
		 return $this->hasOne(Staff::className(), ['user_id' => 'id']);
    }
	
	public static function setPassword($password)
    {
	  return  Yii::$app->security->generatePasswordHash($password);
	}
	
	public function getRoute()
    {
		 return $this->hasOne(Route::className(), ['id' => 'route_id']);
    }
	
	public function getRouteName()
    {
		$route = $this->route;
		
		return isset($route->route_name) ? $route->route_name : null;
    } 
	public function getUserName()
    {
		$user = $this->staff;
		
		return isset($user->first_name) ? ($user->first_name. ' '.$user->last_name ) : null;
    } 

   
}
