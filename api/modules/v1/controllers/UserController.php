<?php

namespace api\modules\v1\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use common\models\User;
use common\models\LoginForm;
use frontend\models\SignupForm;
use backend\models\Role;
use backend\models\Users;
use backend\models\Staff;
use backend\models\Address;
use backend\models\XrefUserRole;
use yii\helpers\Json;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use backend\models\DefaultSetting;
use backend\models\State;
use backend\models\City;

/**
 * User Controller API

 */
class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';    
	
	public function behaviors()
	{
		$behaviors = parent::behaviors();
		 /* $behaviors['authenticator'] = [
			'class' => HttpBasicAuth::className(),
		];  */
		$behaviors['bootstrap'] = [
            'class' => ContentNegotiator::className(),
			'formats' => [
				'application/json' => Response::FORMAT_JSON,
			],
		];  
		return $behaviors;
	}
	

   public function actionIsregister()
   {
        
    	if(!isset($_POST['deviceId']))
		{
            return [
				"status" => "000001",
				"msg" => "Please enter Device ID" 
			];
		}
		$deviceId = $_POST['deviceId'];

		if($user = User::findByDeviceID($deviceId))
		{
			return [
				"status" => "000000",
				"msg" => "device id registered " ,
				'user_id'=> $user->id,
				"role_id" =>$user->role,			
				'is_profile'=> $user->first_login,
				'user_mobile'=> $user->username,
			];
		}
		else{

			return [
				"status" => "000001",
				"msg" => "device id not registered " ,
			];
		}

   }



    public function actionSignup()
    {
        
    	if(!isset($_POST['mobile']) || !isset($_POST['deviceId']))
		{
            return [
				"status" => "000001",
				"msg" => "mobile number or device id not set" 
			];
		}
		
		$mobile = $_POST['mobile'];
		$deviceId = $_POST['deviceId'];
		if($user = User::findByUsername($mobile))
		{
			$model = $this->findModel($user->id);
			if(isset($model) || ($model != null))
			{
				if( ($user->role == 2) && ($deviceId != $user->device_id))
				{
					return [
					"status" => "000002",
					"role_id" =>$user->role,			
					"msg" => "mobile number already registered, but device changed,Update your device ?." ,
				  ]; 
				}
				else {
					//$role = $model->staffType();
					return [
					"status" => "000000",
					"msg" => "Success." ,
					"profile" => $model,
					"user_id" => $user->id,
					"role_id" =>$user->role
				  ];
				}


			}
			else
				{
					if($user->role != 2)
					{
						return [
							"status" => "000003",
							"role_id" =>$user->role,			
							"msg" => "mobile number already registered, but profile not found." ,
						  ]; 
					}
					else{
					
						if($deviceId != $user->device_id)
						{
							return [
							"status" => "000002",
							"role_id" =>$user->role,			
							"msg" => "mobile number already registered, but device changed." ,
						  ]; 
						}
						else{
							return [
							"status" => "000001",
							"role_id" =>$user->role,			
							"msg" => "mobile number already registered, but profile not found." ,
						  ];
						}
					}
		    }
		}
		if(is_numeric($mobile) && preg_match('/^\d{10}$/', $mobile)){
			if($user = User::findByUsermobile($mobile))
			{
				return [
					"status" => "000007",
					"role_id" =>$user->role,			
					"msg" => "your mobile no. not validate. we will send you otp to validate mobile no." ,
				  ];	
			}
			else
			{
				  // pass
					$user = new User();
					$user->username = $mobile;
					$user->mobile = $mobile;
					$user->generateAuthKey();
					$user->device_id = $deviceId;
					$user->generateSignUpToken();
					$user->role = Role::getRole('CUSTOMER');
					$user->first_login = 1;
					if($user->save()){
						 if($user->sendSmsOtp($user->username,$user->otp))
						{
							return [
								"status" => "000000",
								"msg" => "otp send to your mobile number" ,
								"role_id" =>$user->role,
								'otp'=> $user->otp
							];
						}
						else
						{ 
							return [
								"status" => "000012",
								"role_id" =>$user->role,
								"msg" => "Otp could not send ,retry again!" ,
							];
						}
					}
					//// user insertion failed
				} 
				
		}
		else{
			return [
						"status" => "000011",
						"msg" => "Please enter valid mobile number!" ,
					];
			
		}
       
    }


    public function actionVerifyOtp()
    {
        
    	if(!isset($_POST['otp']) || !isset($_POST['mobile']) || !isset($_POST['deviceId']))
		{
            return [
				"status" => "000001",
				"msg" => "otp value or mobile number or devicedId not set" 
			];
		}
		$otp = $_POST['otp'];
		$mobile = $_POST['mobile'];
		$deviceId = $_POST['deviceId'];
		if($user = User::findBySignUpToken($otp,$mobile))
		{
	        
			if( round(abs(strtotime(date('Y-m-d H:i:s')) - strtotime($user->response_time)) / 60,2) > DefaultSetting::getConfigByName("OTP_TOKEN_TIME_OUT"))
	        {
	            
	            return [
	                'msg' => 'Otp Session Expired.' ,
	                "status" => "000001" 
	                ];
	        }
	        $user->removeOtpToken();
	        $user->save(false);
	        return [
					"status" => "000000",
					"msg" => "otp validate successfully" ,
					'user_id'=> $user->id,
					"role_id" =>$user->role,
					'user_mobile'=> $user->username
				];
	           
	    }
	    else
	    {
	    		return [
					"status" => "000001",
					"msg" => "otp not valid" ,
				];
		}

    }

    public function actionDeviceUpdate()
    {
        
    	if(!isset($_POST['mobile']) || !isset($_POST['deviceId']))
		{
            return [
				"status" => "000001",
				"msg" => "mobile number or device id not set" 
			];
		}
		
		$mobile = $_POST['mobile'];
		$deviceId = $_POST['deviceId'];
	    $user = User::findByUsername($mobile);
        $user->device_id = $deviceId;
  
        if($user->save())
        {
        	return [
				"status" => "000000",
				"msg" => "deviceId updated" ,
				"role_id" =>$user->role,
				'user_id'=> $user->id,
				'is_profile'=> $user->first_login
			];
        }
        else
        {
        	return [
				"status" => "000001",
				"msg" => "deviceId not updated ,retry again!" ,
			];
        }
        
    }

    public function actionResendOtp()
    {
        
    	if(!isset($_POST['mobile']) || !isset($_POST['deviceId']))
		{
            return [
				"status" => "000001",
				"msg" => "mobile number or device id not set" 
			];
		}
		
		$mobile = $_POST['mobile'];
		$deviceId = $_POST['deviceId'];
		$user = User::findByUsermobile($mobile,1);  // 1 stand for neglate status in that function
		//$user = User::findByUsername($mobile);     
        
		$user->generateSignUpToken();
        $user->save(false);
        if($user->sendSmsOtp($user->username,$user->otp))
            {
            	return [
					"status" => "000000",
					"msg" => "success" ,
					"role_id" =>$user->role,		
					'otp'=> $user->otp 
				];
            }
            else
            {
            	return [
					"status" => "000001",
					"msg" => "Otp could not send ,retry again!" ,
				];
            }
    }

    public function actionProfile()
    {
        
    	if(!isset($_POST['user_id']) || !isset($_POST['deviceId']))
		{
            return [
				"status" => "000001",
				"msg" => "user id or device id not set" 
			];
		}
		
		$user_id = $_POST['user_id'];
		$deviceId = $_POST['deviceId'];
		if($user = User::findIdentity($user_id))
		{
			$model = $this->findModel($user_id);
			if(!isset($model) || ($model == null))
			{
				$mod_address = new Address();
				$mod_address->address1 = $_POST['address1'];
				$mod_address->address2 = $_POST['address2'];
				$mod_address->city = $_POST['city'];
				$mod_address->country_id = 1;   //$_POST['country_id'];
				$mod_address->state_id = 1;    //$_POST['state_id'];	
				$mod_address->pincode = $_POST['pincode'];
				if($mod_address->validate())
				{
					$mod_address->save();
					$model = new Staff();
					$model->address_id = $mod_address->id;	
					$model->first_name = $_POST['first_name'];
					$model->last_name = $_POST['last_name'];
					$model->phone = $_POST['phone'];	
					$model->email = $_POST['email'];
					$model->user_id = $user_id;
					$model->staff_type = 'CUSTOMER';

					/*$imgdata = base64_decode($_POST['profile_pic']);

					$f = finfo_open();

					$mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
					Yii::$app->params['uploadPath'] = Yii::getAlias('@webroot') . '/uploads/';
		            //$image = UploadedFile::getInstance($model, 'profile_pic');
		            if(isset($image))
		            {
		                $ext = end((explode(".", $image->name)));
		                // generate a unique file name
		                $filename = Yii::$app->security->generateRandomString().".{$ext}";
		                $model->profile_pic = Yii::$app->request->baseUrl .'/uploads/'.$filename;
		                // the path to save file, you can set an uploadPath
		                // in Yii::$app->params (as used in example below)
		                $path = Yii::$app->params['uploadPath'] . $filename;
		                $model->profile_pic_name = $image->name;
		            }*/
		           
		            if($model->save()){
		                /*if(isset($image)){
		                    $image->saveAs($path);
		                }*/
						$user->first_login = 0;
						$user->save();
		                return [
							"status" => "000000",
							"msg" => "successfully added profile",
							"profile" => $model,
							"role_id" =>$user->role,
						];
		            } else {
		                return [
							"status" => "000001",
							"msg" => "profile not added" ,
						];
		            }

				}
				else {
		                return [
							"status" => "000001",
							"msg" => "profile not added" ,
						];
		            }
			}
			else {
		          	$mod_address = $this->findAddressModel($model->address_id);
					if(isset($mod_address) || ($mod_address != null))
					{ 
						//$mod_address = new Address();
						$mod_address->address1 = isset($_POST["address1"])? $_POST['address1']:$mod_address->address1;
						$mod_address->address2 = isset($_POST["address2"])? $_POST['address2']:$mod_address->address2;
						$mod_address->city = isset($_POST["city"])? $_POST['city']:$mod_address->city;
						$mod_address->country_id = isset($_POST["country_id"])? $_POST['country_id']:$mod_address->country_id;
						$mod_address->state_id = isset($_POST["state_id"])? $_POST['state_id']:$mod_address->state_id;	
						$mod_address->pincode = isset($_POST["pincode"])? $_POST['pincode']:$mod_address->pincode;
						if($mod_address->save())
						{
							$model->address_id = $mod_address->id;	
							$model->first_name = isset($_POST["first_name"])? $_POST['first_name']:$model->first_name;
							$model->last_name = isset($_POST["last_name"])? $_POST['last_name']:$model->last_name;
							$model->staff_type = 'CUSTOMER';
							$model->phone = isset($_POST["phone"])? $_POST['phone']:$model->phone;	
							$model->email = isset($_POST["email"])? $_POST['email']:$model->email;
							$model->user_id = $user_id;
				            if($model->save()){
								$user->first_login = 0;
								$user->save();
				        
				                return [
									"status" => "000000",
									"msg" => "successfully updated profile",
									"profile" => $model,
									"role_id" =>$user->role,
								];
				            } else {
				                return [
									"status" => "000001",
									"msg" => "profile not updated" ,
								];
				            }

						}
						else {
				                return [
									"status" => "000001",
									"msg" => "profile not updated" ,
								];
				            }
					}

		        }

		}
		else
        {
        	return [
				"status" => "000001",
				"msg" => "user not found" ,
			];
        }
            
    }


    protected function findModel($id)
    {
		
        if (($model = Staff::find()->where(['user_id' => $id])->One()) !== null) {
            return $model;
        } else {
            return null;
        }
    }

    protected function findAddressModel($id)
    {
		
        if (($model = Address::find()->where(['id' => $id])->One()) !== null) {
            return $model;
        } else {
            return null;
        }
    }
	
	public function actionUserInfo()
    {
        
    	if(!isset($_POST['user_id']) )
		{
            return [
				"status" => "000001",
				"msg" => "user id not set" 
			];
		}
		$result = [];
		$out = [];
		$user_id = $_POST['user_id'];
		$user = User::findIdentity($user_id);
		if($user)
		{
			$userinfo['username'] = $user->username;
			$userinfo['mobile'] = $user->mobile;
			$userinfo['user_id'] = $user->id;
			$out['user'] = $userinfo;
			$member_detail = $this->findModel($user_id);
			if(isset($member_detail) && ($member_detail != null))
			{
				$mod_address = $this->findAddressModel($member_detail->address_id);
				if(isset($mod_address) && ($mod_address != null))
				{
					$out['address'] = $mod_address;
				}
				else{
					$out['address'] = null;
				}
				$out['user_details'] = $member_detail;
			}
			else
			{
				$out['user_details'] = null;
			}
			
			$result['status'] ="000000"; 
			$result['msg'] ="Success"; 
			$result['user_record'] = $out;
			return $result;
			
		}
		else{
			return [
				"status" => "000001",
				"msg" => "user not found" ,
			];
		}
		
		
        
    }
	
	public function actionLogin()
    {
        if(!isset($_POST['username']) || (!isset($_POST['password'])))
		{
            return [
				"status" => "000001",
				"msg" => "User Name or Password Not Set." 
			];
		}
		$ret_obj = [];
		$username = $_POST['username'];
		$password = $_POST['password'];
		
		$res = $this->logininfo($username,$password);
		if($res['status'] != '000000')
		{
			return $res;
		}
		
		$ret_obj['status'] ="000000";
		$ret_obj['msg'] ="Access Granted";
		$ret_obj['user'] = $res['user'];

        return $ret_obj; 
	}
	
	public function logininfo($username,$password)
	{
		$model = new LoginForm();
		$model->username = $username;
		$model->password = $password;
		if(!$model->login(1))
		{
            
			return [
				'msg' => 'Invalid Login' ,
				"status" => "000001" ,
				"login_error" => $model->getErrors(),	
				];
		}
    
		$user = User::findByUsername($username);

		$user_obj['id'] 			= $user->id;
		$user_obj['username'] 		= $user->username;
		$user_obj['is_profile'] 		= $user->first_login;

		$ret_obj['user'] = $user_obj;
		$ret_obj['status'] = "000000";
		return $ret_obj;
   }
   
   public function actionStateByCountry()
   {
		$state = State::find()->where(['active' => 1,'state2country' => 1])->All();
		return [
			"status" => "000000",
			"msg" => "State list for India",
			"state_list" => $state
		];
		
	}
	
	public function actionCityByState()
    {
		if(!isset($_POST['state_id']))
		{
            return [
				"status" => "000001",
				"msg" => "State id Not Set." 
			];
		}
		$state = $_POST['state_id'];
		$city = City::find()->where(['active' => 1,'city2state' => $state])->All();
		if(isset($city) && !empty($city))
		{
			return [
				"status" => "000000",
				"msg" => "City list for given state",
				"city_list" => $city
			];
		}
		else{
			return [
				"status" => "000001",
				"msg" => "City list not found for this state",
			];

		}
		
	}
   
    public function actionChangePassword()
	{
		if(!isset($_POST['username']) || (!isset($_POST['old_password'])) || (!isset($_POST['new_password'])))
		{
            return [
				"status" => "000001",
				"msg" => "User Name or Old Password or New Password Not Set." 
			];
		}
		
		$old_password = $_POST['old_password'];
		$new_password = $_POST['new_password'];
		$identity = User::findIdentity($_POST['username']);
		if(!isset($identity)){
			return [
				'msg' => 'user not found!' ,
				"status" => "000001" ,	
				];
		}
		$username = $identity->username;
		$model = new LoginForm();
		$model->username = $username;
		$model->password = $old_password;
		if(!$model->login(1))
		{
            
			return [
				'msg' => 'Invalid login details either user name or old password not matched !' ,
				"status" => "000001" ,	
				];
		}
    
		$user = User::findByUsername($username);
		if(!isset($user))
		{
			return [
				'msg' => 'username not existing or inactive mode !' ,
				"status" => "000001" 
				];
		}
		$pass_user = Users::findOne($user->id);
		$pass_user->password_hash = Users::setPassword($new_password);
		if($pass_user->save())
		{
			$user_obj['id'] 			= $pass_user->id;
			$user_obj['username'] 		= $pass_user->username;
			$user_obj['is_profile'] 		= $pass_user->first_login;

			$ret_obj['user'] = $user_obj;
			$ret_obj['status'] = "000000";
			$ret_obj['msg'] = "Success";
			return $ret_obj;
		}
		else{
			return [
				'msg' => 'password not updated, try again!' ,
				"status" => "000001" 
				];
		}
		
   }

}
