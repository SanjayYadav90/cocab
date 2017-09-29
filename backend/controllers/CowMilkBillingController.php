<?php

namespace backend\controllers;

use Yii;
use backend\models\CowMilkBilling;
use backend\models\CowMilkBillingSearch;
use backend\models\Delivery;
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
use backend\models\AccountStatement;
/**
 * CowMilkBillingController implements the CRUD actions for CowMilkBilling model.
 */
class CowMilkBillingController extends Controller
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
     * Lists all CowMilkBilling models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CowMilkBillingSearch();
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
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'delivery_status' => $delivery_status,
			'users' =>$users,
			'd_boys' =>$d_boys,
			]);
    }

    /**
     * Displays a single CowMilkBilling model.
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
     * Creates a new CowMilkBilling model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CowMilkBilling();

        $users = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('CUSTOMER')])
			->all();
			
        if ($model->load(Yii::$app->request->post()) ) {
			
			$bill_cycle = $_POST['CowMilkBilling']['bill_cycle'];
			$yearmonth = date("Y-m", strtotime('now'));
			
			$user_id = $_POST['CowMilkBilling']['user_id'];
			if(!isset($user_id) || empty($user_id))
			{
				$subscriptions = Subscription::find()->where(['type'=>'Daily'])->andWhere(['<>', 'status', 0])->asArray()->All();
			}
			else{
				$subscriptions = Subscription::find()->where(['user_id'=>$user_id,'type'=>'Daily'])->andWhere(['<>', 'status', 0])->asArray()->All();
			}
			if(isset($subscriptions )){
				
				$i = 0;
				foreach ($subscriptions as $subs_model)
				{
					$tot_quantity = 0;
					$tot_amount = 0.00;
					//print_r($subs_model);exit;
					$last_bill = CowMilkBilling::find()->where(['user_id' => $subs_model['user_id']])->limit(1)->orderBy('billing_gen_date DESC')->asArray()->One();
					//print_r($last_bill);exit;
					if(isset($last_bill))
					{
						if(strtotime($bill_cycle) == strtotime($yearmonth))
						{
							$start_date = date("Y-m-d",strtotime('+1 day',strtotime($last_bill['end_date']))); 
							$end_date = date("Y-m-d");
						}
						else{
							$start_date = date("Y-m-d",strtotime($last_bill['end_date']));
							$end_date = $bill_cycle.'-31';
						}
					}
					else{
						if(strtotime($bill_cycle) == strtotime($yearmonth))
						{
							$start_date = $bill_cycle.'-01';
							$end_date = date("Y-m-d");
						}
						else{
							$start_date = $bill_cycle.'-01';
							$end_date = $bill_cycle.'-31';
						}
							
							
					}
					//print_r($last_bill);
					//echo"start_date".$start_date;
					//echo"end_date".$end_date;exit;
					$delivery = Delivery::find()->where(['user_id' => $subs_model['user_id'],'product_id' => 1,'subscription_id' => $subs_model['id']])->andWhere(['between', 'delivery_date', $start_date,$end_date])->andWhere(['<>','quantity', -1])->asArray()->All();
					if(isset($delivery) && !empty($delivery))
					{
						foreach ($delivery as $row)
						{
							$tot_quantity = $tot_quantity + $row['delivered'];
							$tot_amount = $tot_amount + ($row['delivered'] * ($row['mrp']- $row['area_discount']));
						}
						
						$account = AccountStatement::find()->where(['user_id' => $subs_model['user_id'], 'payment_status' => 'Received'])->asArray()->All();
						
						$debit = 0;
						$credit = 0;
						if(isset($account) && ($account != null))
						{
							
							foreach ($account as $acc) 
							{
								if($acc['type'] == 'Cr.')
								{
									$credit = $credit + $acc['amount'];
								}
								else
								{
									$debit = $debit + $acc['amount'];
								}
							}		
						}
						$pending_amount = $debit - $credit ; 
						
						//$bill_model = CowMilkBilling::find()->where(['user_id' => $subs_model['user_id'], 'subscription_id' => $subs_model['id'], 'bill_cycle' => $bill_cycle.'-01'])->asArray()->One();
						//$user = User::findOne($subs_model['user_id']);
						$bill_model = new CowMilkBilling();
						$bill_model->subscription_id 	= $subs_model['id'];
						$bill_model->user_id 			= $subs_model['user_id'];
						//$bill_model->delivery_boy_id 	= $user->delivery_boy_id;
						$bill_model->bill_cycle 		= $bill_cycle.'-01';
						$bill_model->start_date 		= $start_date;
						$bill_model->end_date 			= $end_date;
						$bill_model->delivered_quantity = $tot_quantity;
						$bill_model->sub_total 			= $tot_amount;
						$bill_model->referral_discount 	= 0;
						$bill_model->voucher_discount 	= 0;
						$bill_model->tax 				= 0;
						$bill_model->bill_amount 		= (($tot_amount + ($bill_model->tax)) - ( $bill_model->referral_discount + $bill_model->voucher_discount));
						$bill_model->previous_due_amount = $pending_amount;
						$bill_model->net_payable_amount  = $bill_model->bill_amount + $bill_model->previous_due_amount;
						$bill_model->billing_gen_date  	= date('Y-m-d H:i:s');
						if($bill_model->save())
						{
							$i = $i +1;
							$acc_model = new AccountStatement();
							$acc_model->transaction_date = date('Y-m-d H:i:s');
							$acc_model->amount = $bill_model->bill_amount;
							$acc_model->payment_mode = "Bill for ".$bill_cycle;
							$acc_model->user_id = $bill_model->user_id;
							$acc_model->subscription_id = $bill_model->subscription_id;
							$acc_model->type = 'Dr.';
							$acc_model->payment_status = 'Received';
							if($acc_model->save())
							{   
								$message_text ="Dear Customer, Your ".date("M-Y",strtotime($bill_model->bill_cycle))." Bill Amt:Rs ".$acc_model->amount." , Total Due Amt (Inc. Prev. Balance if any): Rs ".$bill_model->net_payable_amount." . Please pay your dues by ".date("d-M-Y",strtotime("+7 day",strtotime(date("Y-m-d"))))." to avoid late payment charge Rs 100 . Doodhvale.com";	 
								User::sendSms($acc_model->user->username,$message_text);
							}
							
						}
						
					}
					
				}
				if($i == count($subscriptions))
				{
					\Yii::$app->getSession()->setFlash('success', 'Successfully generated bill for '.$bill_cycle );
					return $this->render('create', [
						'model' => $model,
						'users' =>$users,
					]);
				}
				else{
					\Yii::$app->getSession()->setFlash('success', 'Successfully generated bill.');
					return $this->render('create', [
						'model' => $model,
						'users' =>$users,
					]);
				}
			}
			else{
				\Yii::$app->getSession()->setFlash('error', 'Don\'t have any active subscription ');
				return $this->render('create', [
					'model' => $model,
					'users' =>$users,
				]);
			}
			
            //return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
				'users' =>$users,
            ]);
        }
    }

    /**
     * Updates an existing CowMilkBilling model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CowMilkBilling model.
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
     * Finds the CowMilkBilling model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CowMilkBilling the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CowMilkBilling::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	
	public function actionGenerateBill()
    {
        $model = new CowMilkBilling();

		$users = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('CUSTOMER')])
			->all();
			
        if ($model->load(Yii::$app->request->post()) ) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
				'users' =>$users,
            ]);
        }
		
    }
	
	public function actionViewBill($id)
    {
        $model = $this->findModel($id);
		
		$result = [];
		$out = [];
		$bill_model =[];
        $new_list= [];
		$delivery_item= [];
		if(isset($model) && ($model != null))
		{
				/* if (($timestamp = strtotime($model->bill_cycle)) !== false)
				{
				  $php_date = getdate($timestamp);
				  $date = date("Y-m-01", $timestamp); // see the date manual page for format options  
				  $date_to = date("Y-m-31", $timestamp);		  
				} */
				$bill_model['bill_no'] 	= $model->id;
				$bill_model['bill_cycle'] 	= date("M-Y" ,strtotime($model->bill_cycle));
				$bill_model['subscription_id'] 	= $model->subscription_id;
				$bill_model['delivered_quantity'] = $model->delivered_quantity;
				$bill_model['sub_total'] 			= $model->sub_total;
				$bill_model['start_date'] 			= $model->start_date;
				$bill_model['end_date'] 			= $model->end_date;
				$bill_model['referral_discount'] 	= $model->referral_discount;
				$bill_model['voucher_discount'] 	= $model->voucher_discount;
				$bill_model['tax'] 				= $model->tax;
				$bill_model['bill_amount'] 		= $model->bill_amount;
				$bill_model['previous_due_amount'] = $model->previous_due_amount;
				$bill_model['net_payable_amount']  = $model->net_payable_amount;
				$bill_model['billing_gen_date']  	= $model->billing_gen_date;
				$delivery = Delivery::find()->where(['user_id' => $model->user_id,'product_id' => 1,'subscription_id' => $model->subscription_id])->andWhere(['between', 'delivery_date', $model->start_date,$model->end_date])->andWhere(['<>','quantity', -1])->All();
				if(isset($delivery) && ($delivery != null))
				{
					foreach ($delivery as $drow) 
					{
						$out['delivery_date'] = $drow->delivery_date;
						$out['delivered'] = $drow->delivered;
						$out['mrp'] = $drow->mrp;
						$out['area_discount'] = $drow->area_discount;
						$out['product'] = $drow->product->name;
						$out['row_amount'] = $drow['delivered'] * ($drow['mrp']- $drow['area_discount']);
						
						array_push($delivery_item,$out);
					}		
				}
				$bill_model['billing_detail'] = $delivery_item;
				array_push($new_list,$bill_model);		
		}
		/* else{
			return [
				"status" => "000001",
				"msg" => "You dont have any bill ." 
				];
		} */
		//$result["bill_list"] = isset($new_list) ? $new_list : null;
		return $this->render('_delivery-bill', [
                'model' => $model,
				'delivery_item' =>$delivery_item,
            ]);
        
    }
}
