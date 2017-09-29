<?php

namespace backend\controllers;

use Yii;
use backend\models\ProductBrandName;
use backend\models\ProductBrandNameSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\DefaultSetting;
use yii\helpers\Json;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * ProductBrandNameController implements the CRUD actions for ProductBrandName model.
 */
class ProductBrandNameController extends Controller
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
     * Lists all ProductBrandName models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductBrandNameSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$status = DefaultSetting::find()->where(['type'=> 'price'])->all();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'status'=>$status
        ]);
    }

    /**
     * Displays a single ProductBrandName model.
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
     * Creates a new ProductBrandName model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProductBrandName();
		$status = DefaultSetting::find()->where(['type'=> 'price'])->all();
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->params['uploadPath'] = Yii::getAlias('@webroot') . '/uploads/';
            $image = UploadedFile::getInstance($model, 'image');
            if(isset($image))
            {
                $temp_ext = explode(".", $image->name);
				$ext = end($temp_ext);
                // generate a unique file name
                 $filename = Yii::$app->security->generateRandomString().".{$ext}";
                $model->image = Yii::$app->request->baseUrl .'/uploads/'.$filename;
                $path = Yii::$app->params['uploadPath'] . $filename;
            }
			
            // $model->image_name = $image->name;
            if($model->save()){
                if(isset($image)){
                    $image->saveAs($path);
                }
                return $this->redirect(['index']);
            } else {
                return $this->render('create', [
					'model' => $model,
					'status'=>$status
				]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
				'status'=>$status
            ]);
        }
    }

    /**
     * Updates an existing ProductBrandName model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$status = DefaultSetting::find()->where(['type'=> 'price'])->all();
		 $old_image = $model->image;
        if ($model->load(Yii::$app->request->post()) ) {
			
            Yii::$app->params['uploadPath'] = Yii::getAlias('@webroot') . '/uploads/';
            $image = UploadedFile::getInstance($model, 'image');
            //print_r($_POST);ex
			if(isset($image))
            {
                $temp_ext = explode(".", $image->name);
				$ext = end($temp_ext);
                // generate a unique file name
                $filename = Yii::$app->security->generateRandomString().".{$ext}";
                $model->image = Yii::$app->request->baseUrl .'/uploads/'.$filename;
                // the path to save file, you can set an uploadPath
                // in Yii::$app->params (as used in example below)
                $path = Yii::$app->params['uploadPath'] . $filename;
                //$model->image_name = $image->name;
                
            }
            else{
                $model->image = $old_image;
            }
            if($model->save()){
                if(isset($image)){
                    $image->saveAs($path);
                }
                return $this->redirect(['index']);
            } else {
					return $this->render('create', [
					'model' => $model,
					'status'=>$status
				]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
				'status'=>$status
            ]);
        }
    }

    /**
     * Deletes an existing ProductBrandName model.
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
     * Finds the ProductBrandName model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProductBrandName the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProductBrandName::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
