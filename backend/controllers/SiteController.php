<?php
namespace backend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\helpers\Url;
/**
 * Site controller
 */
class SiteController extends Controller
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
                        'actions' => ['login', 'error','payment','payment-response'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index','payment','payment-response'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (!\Yii::$app->user->isGuest) {
			return $this->render('index');
		}
		else{
			return $this->redirect(['/site/login']);
		}
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
	
	public function actionPayment()
    {
        //$this->layout = 'main';

         $request = Yii::$app->request->bodyParams;

        if (isset($request['subscribe'])) {

            $params = [
                'ORDER_ID' => '001',
                'CUST_ID' => '451',
                'TXN_AMOUNT' => '200',
                'EMAIL' => 'psshukla.90@gmail.com',
                'MOBILE_NO' => '9582354901'
            ];

            \common\components\Paytm::configPaytm($params, 'test');
        } 

        return $this->render('payment');
    }

    public function actionPaymentResponse()
    {
        
		$response = Yii::$app->request->bodyParams;
	    if (isset($response['order_status']) && $response['order_status'] === 'Success') {

           if(isset($_POST['GATEWAYNAME']) && ($_POST['GATEWAYNAME']=='WALLET')){
            $TXN_AMOUNT = $_POST['TXNAMOUNT'];
            $TXN_ID = $_POST['TXN_ID'];
           }
        Yii::$app->session->setFlash('success', '<br>Thank you for shopping with us. Your credit card has been charged and your transaction is successful. We will be shipping your order to you soon.');
            return $this->redirect(['payment']);
        }
       
        else {
            Yii::$app->session->setFlash('error', "<br>Security Error. Illegal access detected");
            return $this->redirect(['payment']);
        }
    }
}
