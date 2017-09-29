<?php
namespace backend\models;
use Yii;use backend\models\Role;use common\models\User;use backend\models\Users;use backend\models\Staff;
/**
 * This is the  class for config default setting.
 *
 */
class ConfigDefaultSetting extends yii\base\Model
{	public function getSenderType() {	 		return ["all"=>"All","delivery_boy"=>"Delivery Boy"];	 	}
	public function getSenderListForAll() {	 		return [		"all_system_user"=>"All System User",		"all_delivery_boy"=>"All Delivery Boy",		"all_unsettled_user"=>"All Unsettled User",		"all_subscription"=>"All Active Subscribed User",		"all_unsubscription"=>"All Unsubscribed User",		"all_pending_due_amt"=>"All Pending Due Amout",		"all_paused_delivery"=>"All Paused Delivery",		"all_delivered_delivery"=>"All Delivered Delivery",		"all_pending_delivery"=>"All Pending Delivery "		];	}		public function getSenderListForDeliveryBoy() {	 		$d_boys = Staff::find()			->innerJoinWith('users', 'Staff.user_id = Users.id')			->andWhere(['user.status' => User::STATUS_ACTIVE])			->andWhere(['user.role' => Role::getRole('DELIVERY BOY')])			->all();				return $d_boys;	}	public static $Template_MAP = [		'{PENDING_DUES}' => 'pending_dues',		'{PENDING_BILL}' => 'pending_bill',		'{LAST_BILL_AMT}' => 'last_bill_amt',		'{CURR_DATE}' => 'curr_date',		'{CURR_TIME}' => 'curr_time',		'{DUE_DATE}' => 'due_date',		'{F_NAME}' => 'first_name',		'{L_NAME}' => 'last_name',		'{TOTAL_DELIVERY_QTY}' => 'total_delivery_qty',				'{TODAY_DELIVERY}' => 'today_delivery',				'{TOMORROW_ORDER}' => 'tomorrow_order',				'{TODAY_COLLECTION}' => 'today_collection',				'{LAST_BILL_PENDING_AMT}}' => 'last_bill_pending_amt',				'{TOTAL_OUTSTANDING}' => 'total_outstanding',				'{TODAY_EMPTY_COLLECTION}' => 'today_empty_collection',				'{TOTAL_OUTSTANDING}' => 'total_outstanding',				];
}



?>