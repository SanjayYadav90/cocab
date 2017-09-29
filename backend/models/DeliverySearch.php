<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Delivery;
use yii\data\SqlDataProvider;

/**
 * DeliverySearch represents the model behind the search form about `backend\models\Delivery`.
 */
class DeliverySearch extends Delivery
{
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['user_id','delivery_date'], 'required', 'on' => 'customersearchview'],
            [['id', 'product_id','unsettled','subscription_id', 'user_id','area_discount', 'address_id','empty_bottle','broken_bottle','pending_bottle', 'delivery_boy_id', 'isdeliver', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['delivery_date', 'delivery_time','mobile','area','offer_unit','unit','amount'], 'safe'],
            [['quantity','delivered','mrp', 'offer_price', 'discounted_mrp'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
		
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Delivery::find()->orderBy('id DESC');
		
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'product_id' => $this->product_id,
			'subscription_id' => $this->subscription_id,
            'user_id' => $this->user_id,
            'delivery_date' => $this->delivery_date,
            'address_id' => $this->address_id,
			'delivery_time' => $this->delivery_time,
            'quantity' => $this->quantity,
			'delivered' => $this->delivered,
			'empty_bottle' => $this->empty_bottle,
			'broken_bottle' => $this->broken_bottle,
			'pending_bottle' => $this->pending_bottle,
            'delivery_boy_id' => $this->delivery_boy_id,
            'isdeliver' => $this->isdeliver,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
			 'unsettled' => $this->unsettled,
        ]);

        $query->andFilterWhere(['like', 'area', $this->area]);
		$query->andFilterWhere(['=', 'user_id', $this->mobile]);

        return $dataProvider;
    }
	
	public function search2($params)
    {
        
		$d_date = $params['DeliverySearch']['delivery_date'];
		$d_boy = isset($params['DeliverySearch']['delivery_boy_id']) ? $params['DeliverySearch']['delivery_boy_id'] : null;
		$connection = Yii::$app->getDb();
		$totalCount =   0;
		$limit      =   20;
		$from       =   (isset($_GET['page'])) ? ($_GET['page']-1)*$limit : 0; // Match according to your query string
		if(isset($_GET['_togba4293ca']) && $_GET['_togba4293ca'] == 'all' )
		{
			$allpage = true;
		}
		else
		{
			$allpage = false;
		}
		if(!isset($d_boy) || $d_boy == '' ){
			$sql = "select ana.distance, rou.route_name,acc.total as amount ,del.delivery_boy_id, sum(del.quantity) as quantity,
				sum(del.delivered) as delivered ,sum(del.empty_bottle) as empty_bottle,sum(del.broken_bottle) as broken_bottle, 
				sum(del.pending_bottle) as pending_bottle  from delivery del left join (select travel_date,delivery_boy_id ,distance from delivery_analytics where travel_date= '$d_date') ana on ana.delivery_boy_id = del.delivery_boy_id 
				left join (select delivery_boy_id ,route_name from route ) rou on rou.delivery_boy_id = del.delivery_boy_id left join (select delivery_boy_id ,sum(amount) as 'total',transaction_date from account_statement where transaction_date = '$d_date' group by delivery_boy_id) acc on acc.delivery_boy_id = del.delivery_boy_id  
				where del.delivery_date = '$d_date' and del.product_id = 1 and del.quantity != -1
				group by del.delivery_boy_id";
		}
		else{
			$sql = "select ana.distance, rou.route_name,acc.total as amount ,del.delivery_boy_id, sum(del.quantity) as quantity,
				sum(del.delivered) as delivered ,sum(del.empty_bottle) as empty_bottle,sum(del.broken_bottle) as broken_bottle, 
				sum(del.pending_bottle) as pending_bottle  from delivery del left join (select travel_date,delivery_boy_id ,distance from delivery_analytics where travel_date= '$d_date') ana on ana.delivery_boy_id = del.delivery_boy_id 
				left join (select delivery_boy_id ,route_name from route ) rou on rou.delivery_boy_id = del.delivery_boy_id left join (select delivery_boy_id ,sum(amount) as 'total',transaction_date from account_statement where transaction_date = '$d_date' group by delivery_boy_id) acc on acc.delivery_boy_id = del.delivery_boy_id  
				where del.delivery_date = '$d_date' and del.product_id = 1  and del.quantity != -1  and del.delivery_boy_id = $d_boy
				group by del.delivery_boy_id";
		}
		
				
		if($allpage){
			$query  =  Delivery::findBySql($sql);
			
		}
		else{
			$query  =  Delivery::findBySql($sql.' LIMIT '.$from.','.$limit);
		}
        // add conditions that should always apply here
		$count   = $connection->createCommand('SELECT COUNT(*) as total FROM ('.$sql.') a')->queryAll();
		$totalCount     =   $count[0]['total'];
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			 'totalCount' => $totalCount,
			'pagination' => [
				'pageSize' => $limit, 
			],
        ]);

         $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'product_id' => $this->product_id,
			'subscription_id' => $this->subscription_id,
            'user_id' => $this->user_id,
            'delivery_date' => $this->delivery_date,
            'address_id' => $this->address_id,
			'delivery_time' => $this->delivery_time,
            'quantity' => $this->quantity,
			'delivered' => $this->delivered,
			'empty_bottle' => $this->empty_bottle,
			'broken_bottle' => $this->broken_bottle,
			'pending_bottle' => $this->pending_bottle,
            'delivery_boy_id' => $this->delivery_boy_id,
            'isdeliver' => $this->isdeliver,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'area', $this->area]);
		$query->andFilterWhere(['=', 'user_id', $this->mobile]); 

        return $dataProvider;
    }
	
	public function search3($params)
    {
        $query = Delivery::find()->select('user_id, delivery_boy_id, sum(quantity) as quantity ,sum(delivered) as delivered, sum(pending_bottle) as pending_bottle, sum(broken_bottle) as broken_bottle,address_id')->groupBy('user_id');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'product_id' => $this->product_id,
			'subscription_id' => $this->subscription_id,
            'user_id' => $this->user_id,
            //'delivery_date' => $this->delivery_date,
            'address_id' => $this->address_id,
			'delivery_time' => $this->delivery_time,
            'quantity' => $this->quantity,
			'delivered' => $this->delivered,
			'empty_bottle' => $this->empty_bottle,
			'broken_bottle' => $this->broken_bottle,
			'pending_bottle' => $this->pending_bottle,
            'delivery_boy_id' => $this->delivery_boy_id,
            'isdeliver' => $this->isdeliver,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'area', $this->area]);
		$query->andFilterWhere(['=', 'user_id', $this->mobile]);

        return $dataProvider;
    }
	
	public function search4($params)
    {
        //$query = Delivery::find()->orderBy('id DESC');
		$connection = Yii::$app->getDb();
		$totalCount =   0;
		$limit      =   20;
		$from       =   (isset($_GET['page'])) ? ($_GET['page']-1)*$limit : 0; // Match according to your query string
		if(isset($_GET['_togba4293ca']) && $_GET['_togba4293ca'] == 'all' )
		{
			$allpage = true;
		}
		else
		{
			$allpage = false;
		}
		$d_date = $params['DeliverySearch']['delivery_date'];
		$d_boy = isset($params['DeliverySearch']['delivery_boy_id']) ? $params['DeliverySearch']['delivery_boy_id'] : null;

		 if(isset($params['DeliverySearch']['user_id']) && $params['DeliverySearch']['user_id'] !=='' )
			$auser_id = "and user_id = ".$params['DeliverySearch']['user_id'] ;
		 else{
			 $auser_id = '';
		 }
		 if((isset($params['DeliverySearch']['user_id']) && $params['DeliverySearch']['user_id'] !==''))
			$duser_id = "and del.user_id = ".$params['DeliverySearch']['user_id'] ;
		 elseif(isset($params['DeliverySearch']['mobile']) && $params['DeliverySearch']['mobile'] !==''){
			 $duser_id = "and del.user_id = ".$params['DeliverySearch']['mobile'] ;
		 }
		 else{
			 $duser_id ='';
		 }
		 
		 if(isset($params['DeliverySearch']['address_id']) && $params['DeliverySearch']['address_id'] !=='' )
			$daddress = "and del.address_id = ".$params['DeliverySearch']['address_id'] ;
		 else{
			 $daddress = '';
		 }
		 
		  /* if(isset($params['DeliverySearch']['mobile']) && $params['DeliverySearch']['mobile'] !=='' )
			$dmobile = "and del.user_id = ".$params['DeliverySearch']['mobile'] ;
		 else{
			 $dmobile = '';
		 } */
		 
		  if(isset($params['DeliverySearch']['user_id']) && $params['DeliverySearch']['isdeliver'] !=='' )
			$dstatus = "and del.isdeliver = ".$params['DeliverySearch']['isdeliver'] ;
		 else{
			 $dstatus = '';
		 }
		
		if(!isset($d_boy) || $d_boy == '' ){
			$sql = "select del.delivery_date ,acc.total as amount ,del.delivery_boy_id, del.quantity,
				del.delivered ,del.empty_bottle,del.broken_bottle, 
				del.pending_bottle ,del.isdeliver,del.user_id, del.pause ,del.unsettled, del.address_id from delivery del  
				left join (select user_id ,sum(amount) as 'total',transaction_date from account_statement where transaction_date = '$d_date' ".$auser_id. " group by user_id) acc on acc.user_id = del.user_id  
				where del.delivery_date = '$d_date' and del.product_id = 1 and del.quantity != -1 ".$duser_id. ' '.$dstatus. ' '.$daddress." order by del.id desc ";
		}
		else{
			$sql = "select del.delivery_date ,acc.total as amount ,del.delivery_boy_id, del.quantity,
				del.delivered ,del.empty_bottle,del.broken_bottle, 
				del.pending_bottle ,del.isdeliver, del.user_id, del.pause ,del.unsettled, del.address_id from delivery del 
				left join (select delivery_boy_id, user_id ,sum(amount) as 'total',transaction_date from account_statement where transaction_date = '$d_date' and delivery_boy_id = $d_boy ". $auser_id. " group by user_id ) acc on acc.user_id = del.user_id   
				where del.delivery_date = '$d_date' and del.product_id = 1 and del.quantity != -1 ".$duser_id. ' '.$dstatus.' '.$daddress. " and del.delivery_boy_id = $d_boy order by del.id desc";
		}
		
				
		if($allpage){
			$query  =  Delivery::findBySql($sql);
			
		}
		else{
			$query  =  Delivery::findBySql($sql.' LIMIT '.$from.','.$limit);
		}
		  
        // add conditions that should always apply here
		$count   = $connection->createCommand('SELECT COUNT(*) as total FROM ('.$sql.') a')->queryAll();
		$totalCount     =   $count[0]['total'];
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			 'totalCount' => $totalCount,
			'pagination' => [
				'pageSize' => $limit, 
			],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'product_id' => $this->product_id,
			'subscription_id' => $this->subscription_id,
            'user_id' => $this->user_id,
            'delivery_date' => $this->delivery_date,
            'address_id' => $this->address_id,
			'delivery_time' => $this->delivery_time,
            'quantity' => $this->quantity,
			'delivered' => $this->delivered,
			'unsettled' => $this->unsettled,
			'empty_bottle' => $this->empty_bottle,
			'broken_bottle' => $this->broken_bottle,
			'pending_bottle' => $this->pending_bottle,
            'delivery_boy_id' => $this->delivery_boy_id,
            'isdeliver' => $this->isdeliver,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'area', $this->area]);
		$query->andFilterWhere(['=', 'user_id', $this->mobile]);

        return $dataProvider;
    }
	
	public function search5($params)
    {
        $query = Delivery::find()->orderBy('id ASC');
		
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'product_id' => $this->product_id,
			'subscription_id' => $this->subscription_id,
            'user_id' => $this->user_id,
            'address_id' => $this->address_id,
			'delivery_time' => $this->delivery_time,
            'quantity' => $this->quantity,
			'delivered' => $this->delivered,
			'empty_bottle' => $this->empty_bottle,
			'broken_bottle' => $this->broken_bottle,
			'pending_bottle' => $this->pending_bottle,
            'delivery_boy_id' => $this->delivery_boy_id,
            'isdeliver' => $this->isdeliver,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'area', $this->area]);
		$query->andFilterWhere(['=', 'user_id', $this->mobile]);

        return $dataProvider;
    }
	
	public function search6($params)
    {
        $query = Delivery::find()->orderBy('id ASC');
		
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => [
				'pageSize' => 40, 
			],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'product_id' => $this->product_id,
			'subscription_id' => $this->subscription_id,
            'user_id' => $this->user_id,
            'address_id' => $this->address_id,
			'delivery_time' => $this->delivery_time,
            'quantity' => $this->quantity,
			'delivered' => $this->delivered,
			'empty_bottle' => $this->empty_bottle,
			'broken_bottle' => $this->broken_bottle,
			'pending_bottle' => $this->pending_bottle,
            'delivery_boy_id' => $this->delivery_boy_id,
            'isdeliver' => $this->isdeliver,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'area', $this->area]);
		$query->andFilterWhere(['=', 'user_id', $this->mobile]);

        return $dataProvider;
    }
}
