<?php

namespace api\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\User;
use api\modules\v1\models\UserFriend;
use yii\db\Query;

/**
 * UserSearch represents the model behind the search form about `api\models\User`.
 */
class UserSearch extends User
{
    public $full_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'id',
                    'status',
                    'gender',
                    'weight',
                    'height',
                    'level_id',
                    'preview_page',
                    'created_at',
                    'updated_at'
                ], 'integer'],
            [
                [
                    'first_name',
                    'last_name',
                    'email',
                    'birth_date',
                    'postal_code',
                    'full_name',
                ],
                'safe'
            ],
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
     * Find Users for add friend request
     * Only can find full name parameter
     * Searching exclude
     *  * Current session user
     *  * User(s) which have activity for current session user
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchFriend($params)
    {
        /* @var $User User */
        $User = Yii::$app->user->identity;
        User::setFindScenario(User::SCENARIO_SEARCH_FRIEND);
        $query = User::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ]
        ]);

        $this->load($params, '');
        $this->cleanupWhiteSpaces();
        $friendIds = UserFriend::retrieveFriendIds();
        $query->where([ 'not in', 'id', $friendIds ]);
        $query->andFilterWhere(
            [
                'or',
                ['like', 'CONCAT_WS( " ",first_name, last_name)', $this->full_name],
                ['like', 'CONCAT_WS( " ",last_name, first_name)', $this->full_name]
            ]
        );
        $query->andWhere(['<>','id', $User->getId()]);
        $query->active();

        return $dataProvider;
    }

    /**
     * @return $this
     */
    private function cleanupWhiteSpaces()
    {
        $this->full_name = preg_replace('~\s+~', ' ', $this->full_name);
        $this->full_name = trim($this->full_name);
        return $this;
    }
}