<?php

namespace backend\controllers;

use Yii;
use backend\models\Staff;
use backend\models\StaffSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use common\models\User;
use backend\models\Role;
use backend\models\XrefUserRole;
use backend\models\Address;
use backend\models\Country;
use backend\models\State;
use backend\models\Users;
use backend\models\Route;



/**
 * StaffController implements the CRUD actions for Staff model.
 */
class StaffController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
         'access' => [
                'class' => AccessControl::className(),
               'rules' => [
                        [
                            'allow' => false, // Do not have access
                            'roles'=>['?'], // Guests '?'
                        ],
                        // allow authenticated users
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Staff models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StaffSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		//echo"<pre>";print_r($dataProvider);echo"</pre>";exit;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Staff model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Staff model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Staff();
		$mod_users = new Users();
		$mod_address = new Address();
		//$mod_users->scenario = 'staff';
		$country = Country::find()->all();
		$route = Route::find()->all();
		$role = Role::find()->all();
		$state = State::find()->where(['active'=> 1])->all();
		
		
       if ($model->load(Yii::$app->request->post())) {
			$model->attributes=$_POST['Staff'];
			$mod_users->attributes=$_POST['Users'];
			$mod_address->attributes=$_POST['Address'];
			$username = $_POST['Users']['username'];
			$mod_users->username = $username;
			$mod_users->status = User::STATUS_ACTIVE;
			$mod_users->device_id = $username;
			$mod_users->mobile = $username;
			$mod_users->first_login = 1;
			$mod_users->role = $_POST['Users']['role'];
			$mod_users->route_id = $_POST['Users']['route_id'];
		    $mod_users->auth_key = Yii::$app->security->generateRandomString();
			if($mod_users->role != 2){
						$pwd = 1111;
						$pwd_hash = Users::setPassword($pwd);  
						$mod_users->password_hash = $pwd_hash;
				}
				
				if($mod_users->validate())
				{
					$mod_users->save();
					
					$model->user_id = $mod_users->id;
					$address = new Address();
					$address->address1 = $_POST['Address']['address1'];
					$address->address2 = $_POST['Address']['address2'];
					$address->city = $_POST['Address']['city'];
					$address->country_id = $_POST['Address']['country_id'];
					$address->state_id = $_POST['Address']['state_id'];	
					$address->pincode = $_POST['Address']['pincode'];
					if($address->validate())
					{					
						$address->save();
						   $model->address_id = $address->id;
						   //Yii::$app->params['uploadPath'] = Yii::getAlias('@webroot') . '/uploads/';
							//$image = UploadedFile::getInstance($model, 'profile_pic');
						/* 	if(isset($image))
							{
								$ext = end((explode(".", $image->name)));
								// generate a unique file name
								$filename = Yii::$app->security->generateRandomString().".{$ext}";
								$model->profile_pic = $filename; //Yii::$app->request->baseUrl .'/uploads/'.
								
								$path = Yii::$app->params['uploadPath'] . $filename;
								$model->profile_pic_name = $image->name;
							} */
							if($model->validate())
							{
								$model->save();
								$mod_users->first_login = 0;
								$mod_users->save();
								/* if(isset($image)){
									$image->saveAs($path);
								} */
								return $this->redirect(['index']);
								
							}
							else{
								Yii::$app->getSession()->setFlash('error', "Profile not saved Try agian! "); 
						
								return $this->render('create', [
									'model' => $model,
									'mod_users' => $mod_users,
									'mod_address' => $mod_address,
									'country' => $country,
									'state' => $state,
									'role' => $role,
									'route' => $route,
								]);
							}
					}
					
				}
				else{
				
					$user = User::findByUsername($username);
					if(isset($user))
					{
						
						Yii::$app->getSession()->setFlash('error', "This mobile number is already registered. Try another number! "); 
						
					}
					return $this->render('create', [
						'model' => $model,
						'mod_users' => $mod_users,
						'mod_address' => $mod_address,
						'country' => $country,
						'state' => $state,
						'role' => $role,
						'route' => $route,
					]);
				}
            
		       } 
			   else {
            return $this->render('create', [
                'model' => $model,
				'mod_users' => $mod_users,
				'mod_address' => $mod_address,
				'country' => $country,
				'state' => $state,
				'role' => $role,
				'route' => $route,
            ]);
        }
    }

    /**
     * Updates an existing Staff model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$role_delivery_boy = 3;
		$role_distributor =  4;
		$mod_users =Users::findOne($model->user_id);
		$pass = $mod_users->password_hash;
        $mod_address = $this->findAddressModel($model->address_id);
		$delivery_boys = Users::find()->where(['role'=> $role_delivery_boy])->All();
		$distributors = Users::find()->where(['role'=> $role_distributor])->All();
		//$mod_users->scenario = 'staff';
		$country = Country::find()->all();
		$route = Route::find()->all();
		$role = Role::find()->all();
		$state = State::find()->where(['active'=> 1,'state2country'=>$mod_address->country_id])->all();
        //$old_image = $model->profile_pic;
        if ($model->load(Yii::$app->request->post()) ) {
			$model->attributes=$_POST['Staff'];
			$mod_users->attributes=$_POST['Users'];
            $mod_address->attributes=$_POST['Address'];
			//$username = $_POST['User']['username'];
			//$mod_users->username = $username;
			//$mod_users->mobile = $username;
			//$mod_users->role = $_POST['User']['role'];
				if($mod_users->role == 2 || $mod_users->role == 3){
					
					$mod_users->distributor_id = $_POST['Users']['distributor_id'];
				}
				if($mod_users->role == 2 ){
					$mod_users->delivery_boy_id = $_POST['Users']['delivery_boy_id'];
				}
				if($mod_users->role != 2){
					 if(isset($_POST['Users']['password_hash']) && !empty($_POST['Users']['password_hash']))
					 {
						$pwd_hash = Users::setPassword($_POST['Users']['password_hash']);  
						$mod_users->password_hash = $pwd_hash;
					 }
					 else{
						 $mod_users->password_hash = $pass;
					 }
				}
			if($mod_users->validate())
			{
				$mod_users->save();
                $mod_address->address1 = $_POST['Address']['address1'];
				$mod_address->address2 = $_POST['Address']['address2'];
				$mod_address->city = $_POST['Address']['city'];
				$mod_address->country_id = $_POST['Address']['country_id'];
				$mod_address->state_id = $_POST['Address']['state_id'];	
				$mod_address->pincode = $_POST['Address']['pincode'];
                if($mod_address->validate())
                { 					
        			$mod_address->save();
                    $model->address_id = $mod_address->id;
					/* Yii::$app->params['uploadPath'] = Yii::getAlias('@webroot') . '/uploads/';
					$image = UploadedFile::getInstance($model, 'profile_pic');
					if(isset($image))
					{
						$ext = end((explode(".", $image->name)));
						// generate a unique file name
						$filename = Yii::$app->security->generateRandomString().".{$ext}";
						$model->profile_pic = $filename;   //Yii::$app->request->baseUrl .'/uploads/'.
						
						$path = Yii::$app->params['uploadPath'] . $filename;
						$model->profile_pic_name = $image->name;
					}
					else{
						$model->profile_pic = $old_image;
					} */
        				if($model->validate())
        				{
							$model->save();
							/* if(isset($image)){
								$image->saveAs($path);
								if(isset($old_image) && !empty($old_image) && file_exists(getcwd().'/uploads/' .$old_image))
								{
									unlink(getcwd().'/uploads/' .$old_image);
								}
							} */
							Yii::$app->getSession()->setFlash('success', 'Successfully Saved. </br>');
							return $this->redirect(['index']);
        				}
        				else{
							Yii::$app->getSession()->setFlash('error', 'Something went wrong, please try again.. </br>');
        					return $this->render('update', [
        					'model' => $model,
            				'mod_users' => $mod_users,
                            'mod_address' => $mod_address,
            				'country' => $country,
                            'state' => $state,
							'role' => $role,
							'delivery_boys' => $delivery_boys,
							'distributors' => $distributors,
							'route' => $route,
        					]);
        				}

       	        }
				
			}
			else{
			
				$user = User::findByUsername($username);
				if(isset($user))
				{
					Yii::$app->getSession()->setFlash('error', "This mobile number is already registered , Please try another number. "); 
				}
				return $this->render('update', [
					'model' => $model,
    				'mod_users' => $mod_users,
                    'mod_address' => $mod_address,
    				'country' => $country,
                    'state' => $state,
					'role' => $role,
					'delivery_boys' => $delivery_boys,
					'distributors' => $distributors,
					'route' => $route,
				]);
			}  
            
        } else {
			
            return $this->render('update', [
                'model' => $model,
				'mod_users' => $mod_users,
                'mod_address' => $mod_address,
				'country' => $country,
                'state' => $state,
				'role' => $role,
				'delivery_boys' => $delivery_boys,
				'distributors' => $distributors,
				'route' => $route,
            ]);
        }
    }

    /**
     * Deletes an existing Staff model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Staff model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Staff the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Staff::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	protected function findAddressModel($id)
    {
        if (($mod_address = Address::findOne($id)) !== null) {
            return $mod_address;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	
	public function actionChangedeviceid()
	{			 
		$model = new Staff();
		//$users = User::find()->where(['status'=> User::STATUS_ACTIVE ])->andWhere(['between','role', Role::getRole('DELIVERY BOY'),Role::getRole('DISTRIBUTOR')])->all();
		$users = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			//->andWhere(['between','user.role', Role::getRole('DELIVERY BOY'),Role::getRole('DISTRIBUTOR')])
			//->andWhere(['user.role' => Role::getRole('DELIVERY BOY')])
			->all();
		if ($model->load(Yii::$app->request->post())  ) {
				if(isset($_POST['Staff']) )
				{
					
						$mod_users =User::findIdentity($model->user_id);
						$mod_users->device_id =  $mod_users->username; 
						$mod_users->save();	
					
					\Yii::$app->getSession()->setFlash('success', 'Successfully changed Device id');
					return $this->redirect(['index']);
				}
				else{
					\Yii::$app->getSession()->setFlash('error', 'Something went wrong .Try Again! ');
					return $this->redirect(['index']);
				}
			
		}
		else 
		{
            return $this->render('changeDeviceId', [
				'users' => $users,
				'model' => $model,
            ]);
        }
	}
}
