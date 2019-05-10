<?php

namespace backend\models\search;

use backend\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Contacts;

/**
 * ContactsSearch represents the model behind the search form of `backend\models\Contacts`.
 */
class ContactsSearch extends Contacts
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type','user_id','checkbox'], 'integer'],
            [['message', 'status', 'date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Contacts::find()
            ->select([
                'c.*',
                'u.realm_id',
                'u.first_name',
                'u.last_name',
            ])
            ->from(Contacts::tableName() . ' c ')
            ->leftJoin(User::tableName() . ' u', 'u.id = c.user_id')
            ->orderBy(['id' => SORT_DESC]);
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
            'c.type' => $this->type,
            'c.user_id' => $this->user_id,
            'c.checkbox' => $this->checkbox,
//            'date' => $this->date,
        ]);

        if (!is_null($this->date) && strpos($this->date, ' - ') !== false) {
            list($start_date, $end_date) = explode(' - ', $this->date);
            $query->andFilterWhere(['between', 'c.date', $start_date, $end_date]);

        }
        $query->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['like', 'c.status', $this->status]);

        return $dataProvider;
    }
}
