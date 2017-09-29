<?php

namespace backend\controllers;

use Yii;
use backend\models\Products;
use backend\models\ProductsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use backend\models\DefaultSetting;
use backend\models\Category;
use backend\models\ProductBrandName;
use backend\models\ProductPrice;
use backend\models\DeliverySlotName;
use backend\models\ProductFilter;


/**
 * ProductsController implements the CRUD actions for Products model.
 */
class ProductsController extends Controller
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
     * Lists all Products models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductsSearch();
		$cat_list = Category::find()->all();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$product_status = DefaultSetting::find()->where(['type'=> 'product'])->all();
		$prod_filter = ProductFilter::find()->where(['status'=> 1])->all();
		$slots = DeliverySlotName::find()->where(['status'=> 1])->all();
		$brands = ProductBrandName::find()->where(['status'=> 1])->all();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'product_status'=> $product_status,
			'cat_list' => $cat_list,
			'prod_filter'=>$prod_filter,
			'slots'=>$slots,
			'brands'=>$brands
        ]);
    }

    /**
     * Displays a single Products model.
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
     * Creates a new Products model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Products();
		$product_status = DefaultSetting::find()->where(['type'=> 'product'])->all();
		$cat_list = Category::find()->all();
		$prod_filter = ProductFilter::find()->where(['status'=> 1])->all();
		$slots = DeliverySlotName::find()->where(['status'=> 1])->all();
		$brands = ProductBrandName::find()->where(['status'=> 1])->all();
        if ($model->load(Yii::$app->request->post())) {
           
			if(isset($model->product_filter) && !empty($model->product_filter))
			{
				$array=$model->product_filter; 
				$out = [];
				foreach ($array as $value) {  
				   array_push($out,$value); 
				}
				$model->product_filter = implode(", ",$out);
			}
			else{
				$model->product_filter = null;
			}
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
           $model->image_name = $image->name;
            if($model->save()){
                if(isset($image)){
                    $image->saveAs($path);
                }
                return $this->redirect(['index']);
            } else {
                return $this->render('create', [
                'model' => $model,
				'product_status'=> $product_status,
				'cat_list' => $cat_list,
				'prod_filter'=>$prod_filter,
				'slots'=>$slots,
				'brands'=>$brands
            ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
				'product_status'=> $product_status,
				'cat_list' => $cat_list,
				'prod_filter'=>$prod_filter,
				'slots'=>$slots,
				'brands'=>$brands
            ]);
        }
    }

    /**
     * Updates an existing Products model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$product_status = DefaultSetting::find()->where(['type'=> 'product'])->all();
		$cat_list = Category::find()->all();
		$prod_filter = ProductFilter::find()->where(['status'=> 1])->all();
		$slots = DeliverySlotName::find()->where(['status'=> 1])->all();
		$brands = ProductBrandName::find()->where(['status'=> 1])->all();
        $old_image = $model->image;
		if(isset($model->product_filter) && !empty($model->product_filter))
		{
			$filter=$model->product_filter; 
			$model->product_filter = explode(", ",$filter);
		}
		else{
			$model->product_filter = null;
		}
        if ($model->load(Yii::$app->request->post()) ) {
            
			
			if(isset($model->product_filter) && !empty($model->product_filter))
			{
				$array=$model->product_filter; 
				$out = [];
				foreach ($array as $value) {  
				   array_push($out,$value); 
				}
				$model->product_filter = implode(", ",$out);
			}
			else{
				$model->product_filter = null;
			}
				  
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
                $model->image_name = $image->name;
                
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
				'product_status'=> $product_status,
				'cat_list' => $cat_list,
				'prod_filter'=>$prod_filter,
				'slots'=>$slots,
				'brands'=>$brands
            ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
				'product_status'=> $product_status,
				'cat_list' => $cat_list,
				'prod_filter'=>$prod_filter,
				'slots'=>$slots,
				'brands'=>$brands
            ]);
        }
    }

    /**
     * Deletes an existing Products model.
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
     * Finds the Products model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Products the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Products::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
