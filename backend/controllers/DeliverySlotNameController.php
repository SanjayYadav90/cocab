<?php

namespace backend\controllers;

use Yii;
use backend\models\DeliverySlotName;
use backend\models\DeliverySlotNameSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\DefaultSetting;
use yii\helpers\Json;
use yii\filters\AccessControl;

/**
 * DeliverySlotNameController implements the CRUD actions for DeliverySlotName model.
 */
class DeliverySlotNameController extends Controller
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
     * Lists all DeliverySlotName models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DeliverySlotNameSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DeliverySlotName model.
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
     * Creates a new DeliverySlotName model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DeliverySlotName();
		$status = DefaultSetting::find()->where(['type'=> 'price'])->all();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
				'status'=>$status
            ]);
        }
    }

    /**
     * Updates an existing DeliverySlotName model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$status = DefaultSetting::find()->where(['type'=> 'price'])->all();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
				'status'=>$status
            ]);
        }
    }

    /**
     * Deletes an existing DeliverySlotName model.
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
     * Finds the DeliverySlotName model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DeliverySlotName the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DeliverySlotName::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
