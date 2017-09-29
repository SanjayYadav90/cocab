<?php

namespace backend\controllers;

use Yii;
use backend\models\SmsTemplate;
use backend\models\SmsTemplateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\filters\AccessControl;
use backend\models\DefaultSetting;
/**
 * SmsTemplateController implements the CRUD actions for SmsTemplate model.
 */
class SmsTemplateController extends Controller
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
     * Lists all SmsTemplate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SmsTemplateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SmsTemplate model.
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
     * Creates a new SmsTemplate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SmsTemplate();
		$message_template = DefaultSetting::find()->where(['type'=>'MESSAGE_TEMPLATE_TYPE'])->all();
		$template_cat = DefaultSetting::find()->where(['type'=>'MESSAGE_TEMPLATE_CAT'])->all();
        if ($model->load(Yii::$app->request->post()) ) {
            
			$model->template_key = strtoupper(preg_replace("/[^a-zA-Z0-9]+/", '', $_POST['SmsTemplate']['name']));
			if($model->save())
			{
				return $this->redirect(['index']);
			}
        }

        return $this->renderAjax('create', [
            'model' => $model,
			'message_template'=>$message_template,
			'template_cat'=>$template_cat
        ]);
    }

    /**
     * Updates an existing SmsTemplate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$message_template = DefaultSetting::find()->where(['type'=>'MESSAGE_TEMPLATE_TYPE'])->all();
		$template_cat = DefaultSetting::find()->where(['type'=>'MESSAGE_TEMPLATE_CAT'])->all();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('update', [
            'model' => $model,
			'message_template'=>$message_template,
			'template_cat'=>$template_cat
        ]);
    }

    /**
     * Deletes an existing SmsTemplate model.
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
     * Finds the SmsTemplate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SmsTemplate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SmsTemplate::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
