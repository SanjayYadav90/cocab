<?php

namespace backend\controllers;

use Yii;
use backend\models\ProductPrice;
use backend\models\ProductPriceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\Products;
use yii\helpers\Json;
use backend\models\DefaultSetting;

/**
 * ProductPriceController implements the CRUD actions for ProductPrice model.
 */
class ProductPriceController extends Controller
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
     * Lists all ProductPrice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductPriceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$products = Products::find()->all();
		if (Yii::$app->request->post('hasEditable')) {
			// instantiate your book model for saving
			$priceId = Yii::$app->request->post('editableKey');
			$model =ProductPrice::findOne($priceId);

			// store a default json response as desired by editable
			$out = Json::encode(['output'=>'', 'message'=>'']);

			$posted = current($_POST['ProductPrice']);
			$post = ['ProductPrice' => $posted];

			// load model like any single model validation
			if ($model->load($post)) {
			// can save model or do something before saving model
			$model->save();

			$output = '';

			$out = Json::encode(['output'=>$output, 'message'=>'']);
			}
			// return ajax json encoded response and exit
			echo $out;
			return;
		}
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'products' =>$products
        ]);
    }

    /**
     * Displays a single ProductPrice model.
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
     * Creates a new ProductPrice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProductPrice();
		$products = Products::find()->all();
		$price_status = DefaultSetting::find()->where(['type'=>'price'])->all();
		$offer_flag = DefaultSetting::find()->where(['type'=>'offer_flag'])->all();
		$offer_unit = DefaultSetting::find()->where(['type'=>'offer_unit'])->all();
        if ($model->load(Yii::$app->request->post())) {
			
			if($model->offer_flag == 0){
				
				$model->offer_unit = null;
				$model->offer_price = null;
				$model->discounted_mrp = null;
			}
			
			$model->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
				'products' =>$products,
				'price_status' => $price_status,
				'offer_flag' => $offer_flag,
				'offer_unit' => $offer_unit,
            ]);
        }
    }

    /**
     * Updates an existing ProductPrice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$products = Products::find()->all();
		$price_status = DefaultSetting::find()->where(['type'=>'price'])->all();
		$offer_flag = DefaultSetting::find()->where(['type'=>'offer_flag'])->all();
		$offer_unit = DefaultSetting::find()->where(['type'=>'offer_unit'])->all();
        if ($model->load(Yii::$app->request->post())) {
			
			if($model->offer_flag == 0){
				
				$model->offer_unit = null;
				$model->offer_price = null;
				$model->discounted_mrp = null;
			}
			
			$model->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
				'products' =>$products,
				'price_status' => $price_status,
				'offer_flag' => $offer_flag,
				'offer_unit' => $offer_unit,
            ]);
        }
    }

    /**
     * Deletes an existing ProductPrice model.
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
     * Finds the ProductPrice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProductPrice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProductPrice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
