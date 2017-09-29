<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Subscription;
use yii\data\ArrayDataProvider;

/**
 * SubscriptionSearch represents the model behind the search form about `backend\models\Subscription`.
 */
class SubscriptionSearch extends Subscription
{
    //public $mobile;
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'quantity', 'mobile','product_id', 'status','area_discount','user_id', 'coupon_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['start_date', 'end_date', 'type'], 'safe'],
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
        
		/* if(isset($params['SubscriptionSearch']['start_date']))
		{
			$start_date = $params['SubscriptionSearch']['start_date'];
			$query =  Subscription::find()->alias('sub')->joinWith(['xrefPauseSubscriptions'])->where(['sub.status'=>1])->andWhere(['<=', 'sub.start_date', $start_date])->andWhere(['>=', 'sub.end_date', $start_date]);
			  */
			 /* $query = "select  sub.*,psub.quantity as qty
            from subscription as sub
           join xref_pause_subscription as psub
			on sub.id = psub.subscription_id
            
           where sub.status = 1 and sub.start_date <='2017-04-28' and sub.end_date >='2017-04-28'"; */
		   
		   /* $query = (new Query())
                ->select("subscription.id,subscription.start_date,subscription.end_date,subscription.quantity ,subscription.type,subscription.product_id,subscription.user_id,subscription.status,xref_pause_subscription.quantity as qty,xref_pause_subscription.type as mode")
                ->from('subscription')
                ->join('LEFT OUTER JOIN','xref_pause_subscription','xref_pause_subscription.subscription_id = subscription.id');  
			 */
			 /* $dataProvider = new ActiveDataProvider([
				'query' => $query,
			]); */
			/* echo"<pre>";print_r($dataProvider);echo"</pre>";
			exit; */
	/* 		$data =[];
			
			foreach($query as $row)
			{
				
				$out['id'] = $row->id;
				$out['user_id'] = $row->user_id;
				$out['product_id'] = $row->product_id;
				$out['start_date'] = $row->start_date;
				$out['end_date'] = $row->end_date;
				$out['quantity'] = $row->quantity;
				$out['status'] = $row->status;
				array_push($data,$out);
				
			}
			

			$provider = new ArrayDataProvider([
				'allModels' => $data,
				'pagination' => [
					'pageSize' => 20,
				],
				'sort' => [
					'attributes' => ['id', 'user_id'],
				],
			]);

			// get the rows in the currently requested page
			$dataProvider = $provider->getModels();
			return $dataProvider;
		}
		else  
		$data = [
			['id' => 1, 'user_id' => 129],
			['id' => 2, 'user_id' => 154],
			['id' => 100, 'user_id' => 67],
		];

		$provider = new ArrayDataProvider([
			'allModels' => $data,
			'totalCount' => 5,
			'pagination' => [
				'pageSize' => 10,
			],
			'sort' => [
				'attributes' => ['id', 'user_id'],
			],
		]);

		$rows = $provider->getModels();
		return $rows; */
		$query = Subscription::find()->orderBy(['id'=>SORT_DESC]);
		//$query->joinWith('users');
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
            'start_date' => $this->start_date,
            'date(end_date)' => $this->end_date,
            'quantity' => $this->quantity,
            'product_id' => $this->product_id,
            'user_id' => $this->user_id,
			'status' => $this->status,
            'coupon_id' => $this->coupon_id,
			'area_discount' => $this->area_discount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by
			//'mobile'	=>  $this->mobile
        ]);

        $query->andFilterWhere(['like', 'type', $this->type]);
		$query->andFilterWhere(['=', 'user_id',$this->mobile]);

        return $dataProvider;
    }
	public function search2($params)
    {
        
		if(isset($params['SubscriptionSearch']['start_date']))
		{
			$start_date = $params['SubscriptionSearch']['start_date'];
			$query =  Subscription::find()->joinWith(['xrefPauseSubscriptions','XrefPauseSubscription.subscription_id = Subscription.id'])->where(['<=', 'sub.start_date', $start_date])->andWhere(['>=', 'sub.end_date', $start_date]);
		}  
			 
		//$query = Subscription::find();
		//$query->joinWith('users');
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
            'start_date' => $this->start_date,
            'date(end_date)' => $this->end_date,
            'quantity' => $this->quantity,
            'product_id' => $this->product_id,
            'user_id' => $this->user_id,
			'area_discount' => $this->area_discount,
			'status' => $this->status,
            'coupon_id' => $this->coupon_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by
			//'mobile'	=>  $this->mobile
        ]);

        $query->andFilterWhere(['like', 'type', $this->type]);
		$query->andFilterWhere(['=', 'user_id',$this->mobile]);

        return $dataProvider;
    }
}
