<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Users;

/**
 * UserSearch represents the model behind the search form of `app\models\Users`.
 */
class UserSearch extends Users
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'status'], 'integer'],
            [['username', 'email', 'role', 'date_registered', 'last_login'], 'safe'],
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
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = Users::find();

        // add conditions that should always apply here
        // For example, only show active users by default (optional)
        // $query->andWhere(['status' => Users::STATUS_ACTIVE]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'date_registered' => SORT_DESC,
                ],
                'attributes' => [
                    'user_id',
                    'username',
                    'email',
                    'role',
                    'status',
                    'date_registered',
                    'last_login',
                ],
            ],
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'role' => $this->role, // Exact match for role (dropdown)
            'status' => $this->status, // Exact match for status
        ]);

        // Use LIKE for text searches (username, email)
        $query->andFilterWhere(['like', 'username', $this->username])
              ->andFilterWhere(['like', 'email', $this->email]);

        // Handle date_registered filter properly (search by specific date)
        if (!empty($this->date_registered)) {
            // If it's just a date (no time), search the entire day
            if (strlen($this->date_registered) == 10) { // Format: YYYY-MM-DD
                $query->andFilterWhere(['>=', 'date_registered', $this->date_registered . ' 00:00:00'])
                      ->andFilterWhere(['<=', 'date_registered', $this->date_registered . ' 23:59:59']);
            } else {
                // If it includes time, search exactly
                $query->andFilterWhere(['date_registered' => $this->date_registered]);
            }
        }

        // Handle last_login filter (search by specific date)
        if (!empty($this->last_login)) {
            if (strlen($this->last_login) == 10) { // Format: YYYY-MM-DD
                $query->andFilterWhere(['>=', 'last_login', $this->last_login . ' 00:00:00'])
                      ->andFilterWhere(['<=', 'last_login', $this->last_login . ' 23:59:59']);
            } else {
                $query->andFilterWhere(['last_login' => $this->last_login]);
            }
        }

        return $dataProvider;
    }

    /**
     * Search only active users
     * 
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchActive($params)
    {
        $query = Users::find()->active(); // Use the UsersQuery method

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'date_registered' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Apply filters
        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'role' => $this->role,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
              ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }

    /**
     * Search users by role
     * 
     * @param array $params
     * @param string $role Role to filter by (Admin, Teacher, Parent)
     * @return ActiveDataProvider
     */
    public function searchByRole($params, $role)
    {
        $query = Users::find()->role($role); // Use the UsersQuery method

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'date_registered' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Apply other filters
        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
              ->andFilterWhere(['like', 'email', $this->email]);

        if (!empty($this->date_registered)) {
            if (strlen($this->date_registered) == 10) {
                $query->andFilterWhere(['>=', 'date_registered', $this->date_registered . ' 00:00:00'])
                      ->andFilterWhere(['<=', 'date_registered', $this->date_registered . ' 23:59:59']);
            }
        }

        return $dataProvider;
    }

    /**
     * Search recently active users (logged in within last N days)
     * 
     * @param array $params
     * @param int $days Number of days to look back
     * @return ActiveDataProvider
     */
    public function searchRecentlyActive($params, $days = 30)
    {
        $query = Users::find()->recentlyActive($days); // Use the UsersQuery method

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'last_login' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Apply filters
        $query->andFilterWhere([
            'role' => $this->role,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
              ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }

    /**
     * Search users who never logged in
     * 
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchNeverLoggedIn($params)
    {
        $query = Users::find()->neverLoggedIn(); // Use the UsersQuery method

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'date_registered' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Apply filters
        $query->andFilterWhere([
            'role' => $this->role,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
              ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}