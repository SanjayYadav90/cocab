<?php

namespace backend\models;

use Yii;
use backend\models\Address;
use backend\models\Country;
use backend\models\State;
use backend\models\Role;

/**
 * This is the model class for table "staff".
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $phone
 * @property string $email
 * @property string $profile_pic
 * @property string $profile_pic_name
 * @property integer $address_id
 * @property integer $status
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property Address $address
 * @property Users $users
 */

use yii\behaviors\TimestampBehavior;



class Staff extends \yii\db\ActiveRecord
{
    public $staff_type;
	public $main_mobile;
	public $route;
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_details';
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
            [['first_name', 'user_id'], 'required'],
            [['address_id', 'status', 'user_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['first_name', 'staff_type' ,'route','last_name', 'phone'], 'string', 'max' => 50],
            [['email', 'profile_pic_name','main_mobile'], 'string', 'max' => 100],
            [['profile_pic'], 'safe'],
            [['profile_pic'], 'file'],
            [['address_id'], 'exist', 'skipOnError' => true, 'targetClass' => Address::className(), 'targetAttribute' => ['address_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'phone' => 'Phone',
            'email' => 'Email',
            'profile_pic' => 'Profile Pic',
            'profile_pic_name' => 'Profile Pic Name',
            'address_id' => 'Address',
			'status' => 'Status',
            'user_id' => 'User',
			'staff_type' => 'Staff Type',
			'route' => 'Route Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(Address::className(), ['id' => 'address_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }



    public function getDisplayImage() {
		if(empty($this->profile_pic))
		{
			$no_preview = Yii::$app->request->baseUrl .'/theme/dist/img/no_image.png';
			$html = '<div >';
			$html .= "<img  height='100px' src='" . $no_preview . "' alt= No Image'/>";
			$html .= '</div>';
			return $html;
		}
		else
		{
			$html = '<div >';
			$html .= "<img  height='100px' src='" . Yii::$app->request->baseUrl .'/uploads/'.$this->profile_pic . "' alt='" . $this->profile_pic_name ." />";
			$html .= '</div>';
			return $html;
		}
    }
	
	public function getDisplayAddress() {
		
		$address = $this->address;
		if(isset($address)){
			$country = Country::findOne($address->country_id);
			$state = State::findOne($address->state_id);
			if(isset($address->address2) && !empty($address->address2))
			{
				$add = $address->address1 .', '. $address->address2;
			}
			else
			{
				$add = $address->address1;
			}
			$fullAddress = $add .',<br/>'. $address->city .', '. $state->state .',<br/> '. $country->country .', '. $address->pincode ; 
		}
		else $fullAddress = '';
		return $fullAddress;
				
	}
	public function getStaff()
	{
		
		return isset($this->first_name) ? $this->first_name .' '. $this->last_name : '';
	} 

	public function getStaffType() {
		
		$user = $this->users;
		//return $user->role;
		return isset($user) ? Role::getRoleById($user->role) : '';
				
	}
	
	public function getStaffMobile() {
		
		$user = $this->users;
		return isset($user) ? $user->username : '';
		//return Role::getRoleById($user->role);
				
	}

    
}
