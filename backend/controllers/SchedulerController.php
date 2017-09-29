<?php

namespace backend\controllers;

use Yii;
use backend\models\Scheduler;
use backend\models\SchedulerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\filters\AccessControl;
use backend\models\SmsTemplate;
use backend\models\DefaultSetting;
use backend\models\ConfigDefaultSetting;
use backend\models\Role;
use common\models\User;
use backend\models\Users;
use backend\models\Staff;

/**
 * SchedulerController implements the CRUD actions for Scheduler model.
 */
class SchedulerController extends Controller
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
     * Lists all Scheduler models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SchedulerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Scheduler model.
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
     * Creates a new Scheduler model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Scheduler();
		$template_cat = DefaultSetting::find()->where(['type'=>'MESSAGE_TEMPLATE_CAT'])->all();
		
		
        if ($model->load(Yii::$app->request->post()) ) {
			if(isset($model->sender_list) && !empty($model->sender_list))
			{
				$array=$model->sender_list; 
				$out = [];
				foreach ($array as $value) {  
				   array_push($out,$value); 
				}
				$model->sender_list = implode(", ",$out);
			}
			else{
				$model->sender_list = null;
			}
			$model->next_exec_date = $model->start_date;
			$model->save();

            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
			'template_cat'=>$template_cat,
        ]);
    }

    /**
     * Updates an existing Scheduler model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$template_name =  SmsTemplate::findOne($model->template_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
			'template_name' => $template_name,
			
        ]);
    }

    /**
     * Deletes an existing Scheduler model.
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
     * Finds the Scheduler model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Scheduler the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Scheduler::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
	public function actionTemplate($template)
	{

		$obj_temp =  SmsTemplate::find()->where(['template_cat'=>$template,'type'=>'Admin'])->all();
		
        echo "<option value=''>--- Select Template ---</option>";
        if(count($obj_temp)>0){
            foreach($obj_temp as $row){
                echo "<option value=$row->id> $row->name </option>";
            }
        }
        else{
            //echo "<option value=0>--- Not Set ---</option>";
        }
	}
	
	public function actionTemplatedetails()
    {
        $template_id = $_POST['template_id'];
		$template = SmsTemplate::find()->where(['id'=> $template_id])->one();
		echo isset($template->body) ? ($template->body) : 'not set' ; 
		
    }

    public function actionCustomerSms()
    {
       // return $this->render('view');
        echo "<h1>this is customer view</h1>";
    }
	
	public function actionSenderList($type)
	{

		
		if($type == '2')
		{
			$obj_temp = DefaultSetting::find()->where(['type'=>'sechedular_filters'])->orderby('name ASC')->all();
			echo "<option value=0>--- Select Sender List ---</option>";
			if(count($obj_temp)>0){
				foreach($obj_temp as $row){
					echo "<option value=$row->value> $row->name </option>";
				}
			}
			else{
				//echo "<option value=0>--- Not Set ---</option>";
			} 
		}
		else{
			$obj_temp =  Staff::find()
			->innerJoinWith('users', 'Staff.user_id = Users.id')
			->andWhere(['user.status' => User::STATUS_ACTIVE])
			->andWhere(['user.role' => Role::getRole('DELIVERY BOY')])
			->orderby('first_name,last_name ASC')
			->all();
			
			echo "<option value=0>--- Select Sender List ---</option>";
			if(count($obj_temp)>0){
				foreach($obj_temp as $row){
					echo "<option value=$row->user_id> $row->staff </option>";
				}
			}
			else{
				//echo "<option value=0>--- Not Set ---</option>";
			} 
		}
		
        
	}
}
