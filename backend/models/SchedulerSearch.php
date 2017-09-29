<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Scheduler;

/**
 * SchedulerSearch represents the model behind the search form of `backend\models\Scheduler`.
 */
class SchedulerSearch extends Scheduler
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'template_id', 'created_at', 'updated_at','status'], 'integer'],
            [['name', 'sender_list', 'frequency_type', 'Frequency_value', 'start_date', 'time','next_exec_date'], 'safe'],
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
        $query = Scheduler::find();

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
            'template_id' => $this->template_id,
            'start_date' => $this->start_date,
            'time' => $this->time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
			'next_exec_date' => $this->next_exec_date,
			'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'sender_list', $this->sender_list])
            ->andFilterWhere(['like', 'frequency_type', $this->frequency_type])
            ->andFilterWhere(['like', 'Frequency_value', $this->Frequency_value]);

        return $dataProvider;
    }
}
