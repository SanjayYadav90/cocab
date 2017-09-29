<?php
namespace console\controllers;
 
use Yii;
use yii\helpers\Url;
use yii\console\Controller;
use yii\rest\ActiveController;
use yii\db\Query;
use yii\helpers\Json;
use backend\models\Delivery;

/**
 * Test controller
 */
class LoadorderController extends Controller {
 
    public function actionIndex() {
        echo "Yes, cron service is running.";
    }
 
    public function actionHourly() {
        // every hour
        $current_hour = date('G');
        if ($current_hour == 11 || $current_hour == 23) {     // on 11 am hours of day or on 23 pm hours of day
         $model = new Delivery();
		 $model->loadOrders();
    
	  } ///  11 am and 11 pm cron end
    }
	
	 public function actionFrequent() {
         // called every two minutes
        $current_hour = date('G');
        
        $model = new Delivery();
		$model->loadOrders();
    
    }
		
}