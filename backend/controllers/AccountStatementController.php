<?php

namespace backend\controllers;

use Yii;
use backend\models\AccountStatement;
use backend\models\AccountStatementSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\filters\AccessControl;
use backend\models\Staff;
use common\models\User;
use backend\models\Users;
use backend\models\Role;
use backend\models\Subscription;



/**
 * AccountStatementController implements the CRUD actions for AccountStatement model.
 */
class AccountStatementController extends Controller
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
     * Lists all AccountStatement models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AccountStatementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$d_boys = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('DELIVERY BOY')])
			->all();
		$staff = Staff::find()
		->innerJoinWith('users', 'Staff.user_id = Users.id')
		->andWhere(['user.status' => User::STATUS_ACTIVE])
		->andWhere(['user.role' => Role::getRole('CUSTOMER')])
		->all();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'd_boys'	=> $d_boys,
			'staff'	=> $staff
        ]);
    }

    /**
     * Displays a single AccountStatement model.
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
     * Creates a new AccountStatement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AccountStatement();
		
		$staff = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('CUSTOMER')])
			->all();
		$d_boys = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('DELIVERY BOY')])
			->all();
			
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$sub_models = new Subscription();
			$sub_models->unsubscribeFromDeliveryboy($model->user_id);
			return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
				'd_boys' =>$d_boys,
				'staff' => $staff,
				]);
        }
    }

    /**
     * Updates an existing AccountStatement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		//$users = Users::find()->where(['status'=> User::STATUS_ACTIVE,'role'=>Role::getRole('CUSTOMER')])->all();
		$staff = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('CUSTOMER')])
			->all();
			$d_boys = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('DELIVERY BOY')])
			->all();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$sub_models = new Subscription();
			$sub_models->unsubscribeFromDeliveryboy($model->user_id);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
				'staff' =>$staff,
				'd_boys' =>$d_boys,
            ]);
        }
    }

    /**
     * Deletes an existing AccountStatement model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AccountStatement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AccountStatement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AccountStatement::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionPendingAmountReport()
    {
		$searchModel = new AccountStatementSearch();
		$model = new AccountStatement();
		$dataProvider = $searchModel->search4(Yii::$app->request->queryParams);	
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
		//echo"<pre>";print_r($dataProvider);echo"</pre>";exit;
        return $this->render('pending-amount-report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'users' =>$users,
			'd_boys' =>$d_boys,
			'acc_model' =>$model,
        ]);
    }
	
}
