<?php

namespace backend\controllers;

use Yii;
use backend\models\DeliveryAnalytics;
use backend\models\DeliveryAnalyticsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\filters\AccessControl;
use backend\models\DefaultSetting;
use backend\models\Role;
use common\models\User;
use backend\models\Users;
use backend\models\Staff;

/**
 * DeliveryAnalyticsController implements the CRUD actions for DeliveryAnalytics model.
 */
class DeliveryAnalyticsController extends Controller
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
     * Lists all DeliveryAnalytics models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DeliveryAnalyticsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		//$status = DefaultSetting::find()->where(['type'=>'price'])->all();
		$d_boys = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('DELIVERY BOY')])
			->all();
			
			// validate if there is a editable input saved via AJAX
		if (Yii::$app->request->post('hasEditable')) {
			// instantiate your book model for saving
			$dId = Yii::$app->request->post('editableKey');
			$model =DeliveryAnalytics::findOne($dId);
			// store a default json response as desired by editable
			$out = Json::encode(['output'=>'', 'message'=>'']);
			$posted = current($_POST['DeliveryAnalytics']);
			$post = ['DeliveryAnalytics' => $posted];
			// load model like any single model validation
			if ($model->load($post)) {
			$model->save();
			$output = '';
			$out = Json::encode(['output'=>$output, 'message'=>'']);
			}
			// return ajax json encoded response and exit
			echo $out;
			return ;
		}
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			//'status' => $status,
			'd_boys' =>$d_boys
        ]);
    }

    /**
     * Displays a single DeliveryAnalytics model.
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
     * Creates a new DeliveryAnalytics model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DeliveryAnalytics();
		//$status = DefaultSetting::find()->where(['type'=>'price'])->all();
		$d_boys = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('DELIVERY BOY')])
			->all();
			
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
           return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
				//'status' => $status,
				'd_boys' =>$d_boys
            ]);
        }
    }

    /**
     * Updates an existing DeliveryAnalytics model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		//$status = DefaultSetting::find()->where(['type'=>'price'])->all();
		$d_boys = Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('DELIVERY BOY')])
			->all();
			
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
				//'status' => $status,
				'd_boys' =>$d_boys
            ]);
        }
    }

    /**
     * Deletes an existing DeliveryAnalytics model.
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
     * Finds the DeliveryAnalytics model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DeliveryAnalytics the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DeliveryAnalytics::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
