<?php

namespace backend\controllers;

use Yii;
use backend\models\Subscription;
use backend\models\SubscriptionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use common\models\User;
use backend\models\Role;
use backend\models\Users;
use backend\models\Products;
use backend\models\Staff;
use backend\models\XrefPauseSubscription;
use backend\models\AreaDiscount;
use backend\models\ProductPrice;
use backend\models\DefaultSetting;
use backend\models\AccountStatement;


/**
 * SubscriptionController implements the CRUD actions for Subscription model.
 */
class SubscriptionController extends Controller
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
     * Lists all Subscription models.
     * @return mixed
     */
    public function actionIndex()
    {
		$searchModel = new SubscriptionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$discount = AreaDiscount::find()->all();
		$subscription_status = DefaultSetting::find()->where(['type'=>'subscription'])->all();
		// validate if there is a editable input saved via AJAX
		if (Yii::$app->request->post('hasEditable')) {
			// instantiate your book model for saving
			$subscriptionId = Yii::$app->request->post('editableKey');
			$model =Subscription::findOne($subscriptionId);

			// store a default json response as desired by editable
			$out = Json::encode(['output'=>'', 'message'=>'']);

			// fetch the first entry in posted data (there should only be one entry 
			// anyway in this array for an editable submission)
			// - $posted is the posted data for Book without any indexes
			// - $post is the converted array for single model validation
			$posted = current($_POST['Subscription']);
			$post = ['Subscription' => $posted];

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
		
		return $this->render('index', [
            'searchModel' =>  $searchModel,
            'dataProvider' => $dataProvider,
			'discount' => $discount,
			'subscription_status' => $subscription_status,
        ]);
    }

    /**
     * Displays a single Subscription model.
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
     * Creates a new Subscription model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Subscription();
		//$users = Users::find()->where(['status'=> User::STATUS_ACTIVE,'role'=>Role::getRole('CUSTOMER')])->all();
		$users = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('CUSTOMER')])
			->all();
	
		$products = Products::find()->where(['status'=> 1])->all();

        if ($model->load(Yii::$app->request->post()) ) {
			
			if($model->product_id != 1)
			{
				$model->type = 'One Time';
				$model->end_date = $model->start_date;
			}
			else{
				$sub_model = Subscription::findSubscription($model->product_id,$model->user_id);
				if(isset($sub_model)){
					\Yii::$app->getSession()->setFlash('error', 'You can not subscribe, this customer is unsettled or already subscribed.' );
					return $this->render('create', [
						'model' => $model,
						'users' =>$users,
						'products' =>$products
						
					]);
				}
				$model->type = 'Daily';
				$model->end_date = date('Y-m-d',strtotime('+1 year',strtotime($model->start_date)));
			
				$productprice = ProductPrice::find()->where(['status'=> 1,'product_id'=>$model->product_id])->one();
				if(isset($productprice))
				{
					$model->mrp = $productprice->mrp;
					$model->unit = $productprice->unit;
				}
				else{
					$product = Products::findOne($model->product_id);
					$model->mrp = $product->price;
					$model->unit = 'Ltr';
				}
			}
			
			//$model->staff_type  = 'CUSTOMER';
			$model->save();
			$product = Products::find()->where(['id' => $model->product_id])->One();
			$product->popularity = $product->popularity + 1;
			$product->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
				'users' =>$users,
				'products' =>$products
				
            ]);
        }
    }

    /**
     * Updates an existing Subscription model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		//$users = Users::find()->where(['status'=> User::STATUS_ACTIVE,'role'=>Role::getRole('CUSTOMER')])->all();
		$products = Products::find()->where(['status'=> 1])->all();
		$users = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('CUSTOMER')])
			->all();
        if ($model->load(Yii::$app->request->post())) {
            if($model->product_id != 1)
			{
				$model->type = 'One Time';
				$model->end_date = $model->start_date;
			}
			else{
				$model->type = 'Daily';
				$model->end_date = date('Y-m-d',strtotime('+1 year',strtotime($model->start_date)));
				$productprice = ProductPrice::find()->where(['status'=> 1,'product_id'=>$model->product_id])->one();
				if(isset($productprice))
				{
					$model->mrp = $productprice->mrp;
					$model->unit = $productprice->unit;
				}
				else{
					$product = Products::findOne($model->product_id);
					$model->mrp = $product->price;
					$model->unit = 'Ltr';
				}
			}
			//$model->staff_type  = 'CUSTOMER';
			
			$model->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
				'users' =>$users,
				'products' =>$products
            ]);
        }
    }
	
	 /**
     * Unsubscribe an existing Subscription model.
     * If Unsubscribe is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
	
	public function actionUnsubscribe($id)
    {
		$model = $this->findModel($id);
		if(isset($model) && ($model->status == 1 || $model->status == 2))
		{
			/* date_default_timezone_set("Asia/Kolkata");
			if (date('H') >= 19) { 
				 $end_date = date('Y-m-d',strtotime('+2 day',strtotime(date('Y-m-d')))); 	
				}
				else{
					 $end_date = date('Y-m-d',strtotime('+1 day',strtotime(date('Y-m-d'))));
				}  */
			$status = 'Unsubscribed';
			$acc_model = new AccountStatement();
			$account_details = $acc_model->getPendingBillAmountReport($model->user_id,0);
			$pending_amount = $account_details['outstanding_amount'];
			if($pending_amount > 0)
			{
				$model->status = 2;   // unsettled subscription
				$status = 'Unsettled';
			}
			else{
				
				$model->status = 0;  
				$model->end_date = date("Y-m-d");   // inactive subscribe
				$end_date = date("Y-m-d"); 			
			}
			
			if($model->save())
			{
				if($model->status == 0){
					$xref_subscriptions = XrefPauseSubscription::find()->where(['subscription_id' => $model->id])->andwhere(['>=', 'end_date', $end_date])->All();
					if(isset($xref_subscriptions))
					{	
						foreach ($xref_subscriptions as $xref) 
						{
							$xref->delete();
						}
					}
					$model->deleteExtraDelivery();
				}
				\Yii::$app->getSession()->setFlash('success', 'Successfully '.$status);
				return $this->redirect(['index']);
			}
			else{
				\Yii::$app->getSession()->setFlash('error', 'some thing went wrong, try again!');
				return $this->redirect(['index']);
			}
			
		}
		else{
			\Yii::$app->getSession()->setFlash('error', 'Subscription not valid or subscription already unsubscribed.');
			return $this->redirect(['index']);
		}
	}
    /**
     * Deletes an existing Subscription model.
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
     * Finds the Subscription model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Subscription the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Subscription::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	
	public function actionUserdetails()
    {
        $user_id = $_POST['user_id'];
		$user = Users::find()->where(['id'=> $user_id])->one();
		echo isset($user->staff) ? ($user->staff->first_name.' '.$user->staff->last_name) : 'customer mobile not select' ; 
		
    }
	
	public function actionUseraddressdetails()
    {
        $user_id = $_POST['user_id'];
		$staff = Staff::find()->where(['user_id'=> $user_id])->one();
		echo isset($staff->address) ? preg_replace("#<br/>#", " ",$staff->displayAddress) : 'address not set' ;
		
    }
	

	/**
     * Lists all Subscription models with pause supply.
     * @return mixed
     */
    public function actionIndex2()
    {
        
		$searchModel = new SubscriptionSearch();
		//$searchString = Yii::$app->request->get('user_id');
		//print_r($searchString);
        //print_r($_GET);
		if(isset($_GET['SubscriptionSearch']))
		{
			$dataProvider = $searchModel->search2(Yii::$app->request->queryParams);
		}
		else{
			$dataProvider = $searchModel->search(['SubscriptionSearch'=>['user_id' => 0]]);
		}
		
		$users = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('CUSTOMER')])
			->all();
	
		$products = Products::find()->where(['status'=> 1])->all();
		//print_r($users);
		return $this->render('index2', [
            'searchModel' =>  $searchModel,
            'dataProvider' => $dataProvider,
			'users'		   => $users,
			'products'	   => $products
        ]);
    }
	
	
}
