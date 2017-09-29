<?php

namespace backend\controllers;

use Yii;
use backend\models\Delivery;
use backend\models\DeliverySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\XrefPauseSubscription;
use backend\models\Products;
use common\models\User;
use backend\models\Users;
use backend\models\Staff;
use backend\models\Subscription;
use backend\models\DefaultSetting;
use backend\models\Role;
use yii\helpers\Json;
use backend\models\ProductPrice;
use backend\models\AreaDiscount;
use backend\models\CowMilkBilling;

/**
 * DeliveryController implements the CRUD actions for Delivery model.
 */
class DeliveryController extends Controller
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
     * Lists all Delivery models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DeliverySearch();
		if(!isset($_RESPONSE) || empty($_RESPONSE))
		{ 
			//$date_to = date('Y-m-d');
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
			$no_days = 3;
			$date_to = date('Y-m-d',strtotime('+1 day',strtotime(date('Y-m-d'))));
			$date_from = date('Y-m-d',strtotime('-'.$no_days. 'day',strtotime(date('Y-m-d'))));
			$dataProvider->query->andFilterWhere(['between','delivery_date', $date_from,$date_to]);
		}
		else{
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		}
		
        
		$delivery_status = DefaultSetting::find()->where(['type'=>'delivery'])->all();
		$users = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('CUSTOMER')])
			->all();
			
		$d_boys = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('DELIVERY BOY')])
			->all();
		// validate if there is a editable input saved via AJAX
		if (Yii::$app->request->post('hasEditable')) {
			// instantiate your book model for saving
			$deliveryId = Yii::$app->request->post('editableKey');
			$model =Delivery::findOne($deliveryId);

			// store a default json response as desired by editable
			$out = Json::encode(['output'=>'', 'message'=>'']);

			// fetch the first entry in posted data (there should only be one entry 
			// anyway in this array for an editable submission)
			// - $posted is the posted data for Book without any indexes
			// - $post is the converted array for single model validation
			$posted = current($_POST['Delivery']);
			$post = ['Delivery' => $posted];

			// load model like any single model validation
			if ($model->load($post)) {
			// can save model or do something before saving model
			$model->save();

			// custom output to return to be displayed as the editable grid cell
			// data. Normally this is empty - whereby whatever value is edited by
			// in the input by user is updated automatically.
			$output = '';

			// specific use case where you need to validate a specific
			// editable column posted when you have more than one
			// EditableColumn in the grid view. We evaluate here a
			// check to see if buy_amount was posted for the Book model
			/* if (isset($posted['buy_amount'])) {
			$output = Yii::$app->formatter->asDecimal($model->buy_amount, 2);
			} */

			// similarly you can check if the name attribute was posted as well
			// if (isset($posted['name'])) {
			// $output = ''; // process as you need
			// }
			$out = Json::encode(['output'=>$output, 'message'=>'']);
			}
			// return ajax json encoded response and exit
			echo $out;
			return;
		}

		// non-ajax - render the grid by default
		
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'delivery_status' => $delivery_status,
			'users' =>$users,
			'd_boys' =>$d_boys
        ]);
    }

	
	/**
     * Lists all Delivery models which have subscription policy.
     * @return mixed
     */
    public function actionSubscription()
    {
        $searchModel = new DeliverySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$delivery_status = DefaultSetting::find()->where(['type'=>'delivery'])->all();
		$users = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('CUSTOMER')])
			->all();
			
		$d_boys = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('DELIVERY BOY')])
			->all();
		// validate if there is a editable input saved via AJAX
		if (Yii::$app->request->post('hasEditable')) {
			// instantiate your book model for saving
			$deliveryId = Yii::$app->request->post('editableKey');
			$model =Delivery::findOne($deliveryId);

			// store a default json response as desired by editable
			$out = Json::encode(['output'=>'', 'message'=>'']);

			// fetch the first entry in posted data (there should only be one entry 
			// anyway in this array for an editable submission)
			// - $posted is the posted data for Book without any indexes
			// - $post is the converted array for single model validation
			$posted = current($_POST['Delivery']);
			$post = ['Delivery' => $posted];

			// load model like any single model validation
			if ($model->load($post)) {
			// can save model or do something before saving model
			$model->save();

			// custom output to return to be displayed as the editable grid cell
			// data. Normally this is empty - whereby whatever value is edited by
			// in the input by user is updated automatically.
			$output = '';

			// specific use case where you need to validate a specific
			// editable column posted when you have more than one
			// EditableColumn in the grid view. We evaluate here a
			// check to see if buy_amount was posted for the Book model
			/* if (isset($posted['buy_amount'])) {
			$output = Yii::$app->formatter->asDecimal($model->buy_amount, 2);
			} */

			// similarly you can check if the name attribute was posted as well
			// if (isset($posted['name'])) {
			// $output = ''; // process as you need
			// }
			$out = Json::encode(['output'=>$output, 'message'=>'']);
			}
			// return ajax json encoded response and exit
			echo $out;
			return;
		}

		// non-ajax - render the grid by default

		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'delivery_status' => $delivery_status,
			'users' =>$users,
			'd_boys' =>$d_boys
        ]);
    }

    /**
     * Displays a single Delivery model.
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
     * Creates a new Delivery model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Delivery();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Delivery model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$products = Products::find()->where(['status'=> 1])->all();
		$users = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('CUSTOMER')])
			->all();
			
		$d_boys = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('DELIVERY BOY')])
			->all();
		$delivery_status = DefaultSetting::find()->where(['type'=>'delivery'])->all();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
				'delivery_status' => $delivery_status,
				'users' =>$users,
				'd_boys' =>$d_boys,
				'products' =>$products
            ]);
        }
    }

    /**
     * Deletes an existing Delivery model.
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
     * Finds the Delivery model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Delivery the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Delivery::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	/**
	* code for Assign order info into deleivery table.
	*/
	
	public function actionLoad()
	{
		$model = new Delivery();
		$model->loadOrders();
        return $this->redirect(['index']);
	}
	
	public function actionCopydelivery()
	{
		$ids = array_filter(explode(",", $_GET['ids']));
		if(isset($ids))
		{
			foreach($ids as $id)
			{
				$model = $this->findModel($id);
				$model->delivered = $model->quantity; 
				$model->save();	
			}
			\Yii::$app->getSession()->setFlash('success', 'Successfully copy all order as delivery.');
			return $this->redirect(['index']);
		}
		else{
			\Yii::$app->getSession()->setFlash('error', 'Please choose at least one row or multiple rows..');
			return $this->redirect(['index']);
		}
	}
	
	public function actionMarkdelivered()
	{			 
		$model = new Delivery();
		$delivery_status = DefaultSetting::find()->where(['type'=>'delivery'])->all();
		if ($model->load(Yii::$app->request->post())  ) {
			$ids = array_filter(explode(",", $_GET['ids']));
			if(isset($ids))
			{
				if(isset($_POST['Delivery']) )
				{
					foreach($ids as $id)
					{
						$model = $this->findModel($id);
						$model->isdeliver = $_POST['Delivery']['isdeliver'] ; 
						$model->save();	
					}
					\Yii::$app->getSession()->setFlash('success', 'Successfully changed delivery status ');
					return $this->redirect(['index']);
				}
				else{
					\Yii::$app->getSession()->setFlash('error', 'Something went wrong .Try Again! ');
					return $this->redirect(['index']);
				}
			}
			else{
				\Yii::$app->getSession()->setFlash('error', 'Please choose at least one row or multiple rows..');
				return $this->redirect(['index']);
			}
		}
		else 
		{
            return $this->renderAjax('changeDeliveryStatus', [
				'delivery_status' => $delivery_status,
				'model' => $model,
            ]);
        }
	}
	
	public function actionChangedeliveryboy()
	{			 
		$model = new Delivery();
		$d_boys = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('DELIVERY BOY')])
			->all();
		if ($model->load(Yii::$app->request->post())  ) {
			$ids = array_filter(explode(",", $_GET['ids']));
			if(isset($ids))
			{
				if(isset($_POST['Delivery']) )
				{
					foreach($ids as $id)
					{
						$model = $this->findModel($id);
						$model->delivery_boy_id = $_POST['Delivery']['delivery_boy_id'] ; 
						$model->save();	
					}
					\Yii::$app->getSession()->setFlash('success', 'Successfully changed delivery status ');
					return $this->redirect(['index']);
				}
				else{
					\Yii::$app->getSession()->setFlash('error', 'Something went wrong .Try Again! ');
					return $this->redirect(['index']);
				}
			}
			else{
				\Yii::$app->getSession()->setFlash('error', 'Please choose at least one row or multiple rows..');
				return $this->redirect(['index']);
			}
		}
		else 
		{
            return $this->renderAjax('changeDeliveryBoy', [
				'd_boys' => $d_boys,
				'model' => $model,
            ]);
        }
	}
	
	public function actionDailyReport()
    {
        $searchModel = new DeliverySearch();
       
		$searchModel->product_id = 1;
		/* $searchModel->isdeliver = 2; */
		if(isset($_GET['DeliverySearch']['delivery_date']))
		{
			 $d_date = $_GET['DeliverySearch']['delivery_date'];
			 $d_boy = $_GET['DeliverySearch']['delivery_boy_id'];
			 $searchModel->delivery_date = $d_date;
			 $searchModel->delivery_boy_id = $d_boy;
			 
			 $dataProvider = $searchModel->search4(Yii::$app->request->queryParams);
			 $dataProvider->query->andFilterWhere(['<>','quantity', -1]);
			 
		}
		else{
			$dataProvider = $searchModel->search(['DeliverySearch'=>['user_id' => 0]]);
		}
		
		$delivery_status = DefaultSetting::find()->where(['type'=>'delivery'])->all();
		$users = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('CUSTOMER')])
			->all();
			
		$d_boys = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('DELIVERY BOY')])
			->all();
		


		
        return $this->render('daily-report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'delivery_status' => $delivery_status,
			'users' =>$users,
			'd_boys' =>$d_boys
        ]);
    }
	
	public function actionMonthlyReport()
    {
        $searchModel = new DeliverySearch();
       
		$searchModel->product_id = 1;
		/* $searchModel->isdeliver = 2; */
		if(isset($_GET['DeliverySearch']['delivery_date']))
		{
			$date = $_GET['DeliverySearch']['delivery_date'];
			//unset($_GET['DeliverySearch']['delivery_date']);
			$dataProvider = $searchModel->search3(Yii::$app->request->queryParams);
			$dataProvider->query->andFilterWhere(['between','delivery_date', $date.'-01',$date.'-31']);
			$dataProvider->query->andFilterWhere(['<>','quantity', -1]);
			$searchModel->delivery_date = $date;
		}
		else{
			$dataProvider = $searchModel->search(['DeliverySearch'=>['user_id' => 0]]);
		}
		
		$delivery_status = DefaultSetting::find()->where(['type'=>'delivery'])->all();
		$users = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('CUSTOMER')])
			->all();
			
		$d_boys = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('DELIVERY BOY')])
			->all();
		


		
        return $this->render('monthly-report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'delivery_status' => $delivery_status,
			'users' =>$users,
			'd_boys' =>$d_boys
        ]);
    }
	
	public function actionSummaryReport()
    {
        $searchModel = new DeliverySearch();
       
		/* $searchModel->product_id = 1;
		$searchModel->isdeliver = 2; */
		if(isset($_GET['DeliverySearch']['delivery_date']))
		{
			 $dataProvider = $searchModel->search2(Yii::$app->request->queryParams);
		}
		else{
			$dataProvider = $searchModel->search(['DeliverySearch'=>['user_id' => 0]]);
		}
		
		$delivery_status = DefaultSetting::find()->where(['type'=>'delivery'])->all();
		$users = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('CUSTOMER')])
			->all();
			
		$d_boys = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('DELIVERY BOY')])
			->all();
		

		
		
        return $this->render('summary-report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'delivery_status' => $delivery_status,
			'users' =>$users,
			'd_boys' =>$d_boys
        ]);
    }
	
	public function actionCustomerDelivery()
    {
        $searchModel = new DeliverySearch(['scenario' => 'customersearchview']);
       
		$searchModel->product_id = 1;
		/* $searchModel->isdeliver = 2; */
		if(isset($_GET['DeliverySearch']['delivery_date']))
		{
			$date = $_GET['DeliverySearch']['delivery_date'];
			//unset($_GET['DeliverySearch']['delivery_date']);
			//$searchModel->delivery_date = null;
			$dataProvider = $searchModel->search5(Yii::$app->request->queryParams);
			$dataProvider->query->andFilterWhere(['between','delivery_date', $date.'-01',$date.'-31']);
			$dataProvider->query->andFilterWhere(['<>','quantity', -1]);
			$searchModel->delivery_date = $date;
			//print_r($dataProvider);exit;
		}
		else{
			$dataProvider = $searchModel->search5(['DeliverySearch'=>['user_id' => 0,'delivery_date' =>date('Y-m')]]);
		}
		
		$delivery_status = DefaultSetting::find()->where(['type'=>'delivery'])->all();
		$users = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('CUSTOMER')])
			->all();
			
		$d_boys = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('DELIVERY BOY')])
			->all();
		


		
        return $this->render('customer-delivery', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'delivery_status' => $delivery_status,
			'users' =>$users,
			'd_boys' =>$d_boys
        ]);
    }
	
	/**
     * Url action - /delivery/customer-delivery-detail
     */
    public function actionCustomerDeliveryDetail($id) {
        if (isset($_POST['expandRowKey'])) {
            $bill_model = CowMilkBilling::findOne($_POST['expandRowKey']);
			 $searchModel = new DeliverySearch();
			 $searchModel->product_id = 1;
			 $searchModel->user_id = $bill_model->user_id;
			 $searchModel->subscription_id = $bill_model->subscription_id;
			 $d_date = $bill_model->bill_cycle;
			 $dataProvider = $searchModel->search5(Yii::$app->request->queryParams);
			 $dataProvider->query->andFilterWhere(['between','delivery_date', $d_date.'-01',$d_date.'-31']);
			 $dataProvider->query->andFilterWhere(['<>','quantity', -1]);
			 //print_r($dataProvider);exit;
            return $this->renderPartial('_delivery-details', ['dataProvider'=>$dataProvider]);
        } else {
            return '<div class="alert alert-danger">No data found</div>';
        }
    }
}
