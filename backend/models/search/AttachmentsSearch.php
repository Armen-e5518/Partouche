<?php

namespace backend\models\search;

use backend\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Attachments;

/**
 * AttachmentsSearch represents the model behind the search form of `backend\models\Attachments`.
 */
class AttachmentsSearch extends Attachments
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'status','category_id','checkbox'], 'integer'],
            [['comment', 'image', 'realm_id', 'date'], 'safe'],
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
        $query = Attachments::find()
            ->select([
                'a.*',
                'u.realm_id',
                'u.first_name',
                'u.last_name',
            ])
            ->from(Attachments::tableName() . ' a ')
            ->leftJoin(User::tableName() . ' u', 'u.id = a.user_id');
        $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'sort' => [
                    'defaultOrder' => ['id' => SORT_DESC],
                    'attributes' => [
                        'id',
                        'realm_id',
                        'user_id',
                        'comment',
                        'date',
                        'status',
                        'category_id',
                        'checkbox',
                    ]
                ]
            ]
        );
        $this->load($params);
        if (!$this->validate()) {
//         print_r( $this->getErrors());die;
            return $dataProvider;
        }
        $query->andFilterWhere([
//            'id' => $this->id,
            'a.user_id' => $this->user_id,
            'a.status' => $this->status,
            'a.category_id' => $this->category_id,
            'a.checkbox' => $this->checkbox,
//            'u.realm_id' => $this->realm_id,
        ]);
        if (!is_null($this->date) && strpos($this->date, ' - ') !== false) {
            list($start_date, $end_date) = explode(' - ', $this->date);
            $query->andFilterWhere(['between', 'a.date', $start_date, $end_date]);

        }
        $query->andFilterWhere(['like', 'a.comment', $this->comment])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'u.realm_id', $this->realm_id]);

        return $dataProvider;
    }
}
