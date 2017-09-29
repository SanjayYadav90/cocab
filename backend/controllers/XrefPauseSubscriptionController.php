<?php

namespace backend\controllers;

use Yii;
use backend\models\XrefPauseSubscription;
use backend\models\XrefPauseSubscriptionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\filters\AccessControl;
use common\models\User;
use backend\models\Role;
use backend\models\Users;
use backend\models\Products;
use backend\models\Staff;
use backend\models\Subscription;


/**
 * XrefPauseSubscriptionController implements the CRUD actions for XrefPauseSubscription model.
 */
class XrefPauseSubscriptionController extends Controller
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
     * Lists all XrefPauseSubscription models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new XrefPauseSubscriptionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single XrefPauseSubscription model.
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
     * Creates a new XrefPauseSubscription model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new XrefPauseSubscription();
		$users = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('CUSTOMER')])
			->all();
		$products = Products::find()->where(['status'=> 1])->all();
        if ($model->load(Yii::$app->request->post()) ) {
			
			$user_id = $_POST['XrefPauseSubscription']['user_id'];
			$start_date = $_POST['XrefPauseSubscription']['start_date'];
			$product_id = $_POST['XrefPauseSubscription']['product_id'];
			$quantity = $_POST['XrefPauseSubscription']['quantity'];
			$type = $_POST['XrefPauseSubscription']['type'];
			$end_date = $_POST['XrefPauseSubscription']['end_date'];
			$current_date = date("Y-m-d");
			if(strtotime($current_date) > strtotime($start_date))
			{
				Yii::$app->getSession()->setFlash('error', "past date given as start date for edit/pause subscription! ");
				return $this->render('create', [
					'model' => $model,
					'users' =>$users,
					'products' =>$products
				]);
			}
			$start = strtotime($start_date);
			$end = isset($end_date) ? strtotime($end_date) : $start ;
			$no_days = floor(abs($end - $start) / 86400) + 1;
			$date = $start_date;
			$i = 1;
			$subscription = Subscription::find()->where(['user_id' => $user_id,'product_id' =>$product_id,'status'=>1 ])->andWhere(['<=', 'start_date', $end_date])->andWhere(['>=', 'end_date', $start_date])->one();
			if(isset($subscription) && ($subscription != null))
			{
				while($no_days >= $i)
				{
					$pause_items = XrefPauseSubscription::find()->where(['subscription_id' => $subscription->id,'user_id' => $user_id,'start_date' => $date])->One();
					if(!isset($pause_items))
					{
						$pause_items = new XrefPauseSubscription();
						$pause_items->subscription_id = $subscription->id;  
						$pause_items->start_date = $date;
						$pause_items->end_date = $date;
						$pause_items->user_id = $user_id;
						$pause_items->quantity = $quantity;
						$pause_items->type = $type;
						$pause_items->save();			
					}
					else{
						$pause_items->status = 1;
						$pause_items->type = $type;
						$pause_items->quantity = $quantity;
						$pause_items->save();
					}
					$date = date('Y-m-d', strtotime($date .' +1 day'));
					$i++;
				}
				
				Yii::$app->getSession()->setFlash('success', "Subscription created for given user and product for given date. "); 
				return $this->redirect(['index']);
			}
			else{
				Yii::$app->getSession()->setFlash('error', "Subscription not found for this user and product combination for given date! "); 
				return $this->render('create', [
					'model' => $model,
					'users' =>$users,
					'products' =>$products
				]);
			}
        } else {
            return $this->render('create', [
                'model' => $model,
				'users' =>$users,
				'products' =>$products
            ]);
        }
    }

    /**
     * Updates an existing XrefPauseSubscription model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$users = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('CUSTOMER')])
			->all();
		$product_id = $model->subscription->product_id;
		$model->product_id = $product_id;
		$products = Products::find()->where(['status'=> 1])->all();
        if ($model->load(Yii::$app->request->post()) ) {
          
			$user_id = $_POST['XrefPauseSubscription']['user_id'];
			$start_date = $_POST['XrefPauseSubscription']['start_date'];
			$product_id = $_POST['XrefPauseSubscription']['product_id'];
			$quantity = $_POST['XrefPauseSubscription']['quantity'];
			$type = $_POST['XrefPauseSubscription']['type'];
			$end_date = $_POST['XrefPauseSubscription']['end_date'];
			if(empty($end_date))
			{
				$end_date = $start_date;
			}
			$current_date = date("Y-m-d");
			if(strtotime($current_date) > strtotime($start_date))
			{
				Yii::$app->getSession()->setFlash('error', "past date given as start date for edit/pause subscription! ");
				return $this->render('update', [
					'model' => $model,
					'users' =>$users,
					'products' =>$products
				]);
			}
			$start = strtotime($start_date);
			$end = strtotime($end_date);
			$no_days = floor(abs($end - $start) / 86400) + 1;
			$date = $start_date;
			$i = 1;
			$subscription = Subscription::find()->where(['user_id' => $user_id,'product_id' =>$product_id,'status'=> 1 ])->andWhere(['<=', 'start_date', $end_date])->andWhere(['>=', 'end_date', $start_date])->one();
			if(isset($subscription) && ($subscription != null))
			{
				while($no_days >= $i)
				{
					$pause_items = XrefPauseSubscription::find()->where(['subscription_id' => $subscription->id,'user_id' => $user_id,'start_date' => $date])->One();
					if(!isset($pause_items))
					{
						$pause_items = new XrefPauseSubscription();
						$pause_items->subscription_id = $subscription->id;  
						$pause_items->start_date = $date;
						$pause_items->end_date = $date;
						$pause_items->user_id = $user_id;
						$pause_items->quantity = $quantity;
						$pause_items->type = $type;
						$pause_items->save();			
					}
					else{
						$pause_items->status = 1;
						$pause_items->type = $type;
						$pause_items->quantity = $quantity;
						$pause_items->save();
					}
					$date = date('Y-m-d', strtotime($date .' +1 day'));
					$i++;
				}
				
				Yii::$app->getSession()->setFlash('success', "Subscription updated for given user and product for given date. "); 
				return $this->redirect(['index']);
			}
			else{
				Yii::$app->getSession()->setFlash('error', "Subscription not found for this user and product combination for given date! "); 
				return $this->render('update', [
					'model' => $model,
					'users' =>$users,
					'products' =>$products
				]);
			}
		   
		   
        } else {
            return $this->render('update', [
                'model' => $model,
				'users' =>$users,
				'products' =>$products
            ]);
        }
    }

    /**
     * Deletes an existing XrefPauseSubscription model.
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
     * Finds the XrefPauseSubscription model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return XrefPauseSubscription the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = XrefPauseSubscription::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	
}
