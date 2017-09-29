<?php

namespace backend\controllers;

use Yii;
use backend\models\Category;
use backend\models\CategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use backend\models\DefaultSetting;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
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
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
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
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category();
		$cat_status = DefaultSetting::find()->where(['type'=> 'category'])->all();
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
                // the path to save file, you can set an uploadPath
                // in Yii::$app->params (as used in example below)
                $path = Yii::$app->params['uploadPath'] . $filename;
            }
			
            if($model->save()){
                if(isset($image)){
                    $image->saveAs($path);
                }
                return $this->redirect(['index']);
            } else {
                return $this->render('create', [
                'model' => $model,
				'cat_status'=> $cat_status
            ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
				'cat_status'=> $cat_status
            ]);
        }
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $cat_status = DefaultSetting::find()->where(['type'=> 'category'])->all();
        $old_image = $model->image;
        if ($model->load(Yii::$app->request->post()) ) {
           Yii::$app->params['uploadPath'] = Yii::getAlias('@webroot') . '/uploads/';
            $image = UploadedFile::getInstance($model, 'image');
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
				'cat_status'=> $cat_status
            ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
				'cat_status'=> $cat_status
            ]);
        }
    }

    /**
     * Deletes an existing Category model.
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
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
