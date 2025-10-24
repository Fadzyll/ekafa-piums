<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DocumentCategory;

/**
 * DocumentCategorySearch represents the model behind the search form of `app\models\DocumentCategory`.
 */
class DocumentCategorySearch extends DocumentCategory
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id'], 'integer'],
            [['category_name', 'description', 'required_for_role', 'status', 'created_at', 'updated_at'], 'safe'],
            [['is_required'], 'boolean'],
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
        $query = DocumentCategory::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
                'attributes' => [
                    'category_id',
                    'category_name',
                    'required_for_role',
                    'is_required',
                    'status',
                    'created_at',
                    'updated_at',
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
            'category_id' => $this->category_id,
            'is_required' => $this->is_required,
            'required_for_role' => $this->required_for_role, // Exact match for role
            'status' => $this->status, // Exact match for status
        ]);

        // Use LIKE for text searches (category_name, description)
        $query->andFilterWhere(['like', 'category_name', $this->category_name])
            ->andFilterWhere(['like', 'description', $this->description]);

        // Handle created_at filter properly (search by specific date)
        if (!empty($this->created_at)) {
            // If it's just a date (no time), search the entire day
            if (strlen($this->created_at) == 10) { // Format: YYYY-MM-DD
                $query->andFilterWhere(['>=', 'created_at', $this->created_at . ' 00:00:00'])
                      ->andFilterWhere(['<=', 'created_at', $this->created_at . ' 23:59:59']);
            } else {
                // If it includes time, search exactly
                $query->andFilterWhere(['created_at' => $this->created_at]);
            }
        }

        // Handle updated_at filter (search by specific date)
        if (!empty($this->updated_at)) {
            if (strlen($this->updated_at) == 10) { // Format: YYYY-MM-DD
                $query->andFilterWhere(['>=', 'updated_at', $this->updated_at . ' 00:00:00'])
                      ->andFilterWhere(['<=', 'updated_at', $this->updated_at . ' 23:59:59']);
            } else {
                $query->andFilterWhere(['updated_at' => $this->updated_at]);
            }
        }

        return $dataProvider;
    }

    /**
     * Search only active categories
     * 
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchActive($params)
    {
        $query = DocumentCategory::find()->where(['status' => DocumentCategory::STATUS_ACTIVE]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'category_name' => SORT_ASC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Apply filters
        $query->andFilterWhere([
            'category_id' => $this->category_id,
            'required_for_role' => $this->required_for_role,
            'is_required' => $this->is_required,
        ]);

        $query->andFilterWhere(['like', 'category_name', $this->category_name])
              ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

    /**
     * Search inactive categories
     * 
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchInactive($params)
    {
        $query = DocumentCategory::find()->where(['status' => DocumentCategory::STATUS_INACTIVE]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'updated_at' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Apply filters
        $query->andFilterWhere([
            'category_id' => $this->category_id,
            'required_for_role' => $this->required_for_role,
            'is_required' => $this->is_required,
        ]);

        $query->andFilterWhere(['like', 'category_name', $this->category_name])
              ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

    /**
     * Search categories by role requirement
     * 
     * @param array $params
     * @param string $role Role to filter by (Teacher, Parent, Both)
     * @return ActiveDataProvider
     */
    public function searchByRole($params, $role)
    {
        $query = DocumentCategory::find()->where(['required_for_role' => $role]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'category_name' => SORT_ASC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Apply other filters
        $query->andFilterWhere([
            'status' => $this->status,
            'is_required' => $this->is_required,
        ]);

        $query->andFilterWhere(['like', 'category_name', $this->category_name])
              ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

    /**
     * Search only required categories
     * 
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchRequired($params)
    {
        $query = DocumentCategory::find()->where(['is_required' => 1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'category_name' => SORT_ASC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Apply filters
        $query->andFilterWhere([
            'status' => $this->status,
            'required_for_role' => $this->required_for_role,
        ]);

        $query->andFilterWhere(['like', 'category_name', $this->category_name])
              ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

    /**
     * Search only optional categories
     * 
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchOptional($params)
    {
        $query = DocumentCategory::find()->where(['is_required' => 0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'category_name' => SORT_ASC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Apply filters
        $query->andFilterWhere([
            'status' => $this->status,
            'required_for_role' => $this->required_for_role,
        ]);

        $query->andFilterWhere(['like', 'category_name', $this->category_name])
              ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

    /**
     * Search categories for teachers
     * 
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchForTeachers($params)
    {
        $query = DocumentCategory::find()->where([
            'or',
            ['required_for_role' => DocumentCategory::ROLE_TEACHER],
            ['required_for_role' => DocumentCategory::ROLE_BOTH]
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'is_required' => SORT_DESC, // Required first
                    'category_name' => SORT_ASC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Apply filters
        $query->andFilterWhere([
            'status' => $this->status,
            'is_required' => $this->is_required,
        ]);

        $query->andFilterWhere(['like', 'category_name', $this->category_name])
              ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

    /**
     * Search categories for parents
     * 
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchForParents($params)
    {
        $query = DocumentCategory::find()->where([
            'or',
            ['required_for_role' => DocumentCategory::ROLE_PARENT],
            ['required_for_role' => DocumentCategory::ROLE_BOTH]
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'is_required' => SORT_DESC, // Required first
                    'category_name' => SORT_ASC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Apply filters
        $query->andFilterWhere([
            'status' => $this->status,
            'is_required' => $this->is_required,
        ]);

        $query->andFilterWhere(['like', 'category_name', $this->category_name])
              ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

    /**
     * Search categories for both roles
     * 
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchForBothRoles($params)
    {
        $query = DocumentCategory::find()->where(['required_for_role' => DocumentCategory::ROLE_BOTH]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'is_required' => SORT_DESC, // Required first
                    'category_name' => SORT_ASC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Apply filters
        $query->andFilterWhere([
            'status' => $this->status,
            'is_required' => $this->is_required,
        ]);

        $query->andFilterWhere(['like', 'category_name', $this->category_name])
              ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

    /**
     * Search categories created after a specific date
     * 
     * @param array $params
     * @param string $date Date in Y-m-d format
     * @return ActiveDataProvider
     */
    public function searchCreatedAfter($params, $date)
    {
        $query = DocumentCategory::find()->where(['>=', 'created_at', $date]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Apply filters
        $query->andFilterWhere([
            'status' => $this->status,
            'required_for_role' => $this->required_for_role,
            'is_required' => $this->is_required,
        ]);

        $query->andFilterWhere(['like', 'category_name', $this->category_name])
              ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

    /**
     * Search categories created before a specific date
     * 
     * @param array $params
     * @param string $date Date in Y-m-d format
     * @return ActiveDataProvider
     */
    public function searchCreatedBefore($params, $date)
    {
        $query = DocumentCategory::find()->where(['<=', 'created_at', $date]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Apply filters
        $query->andFilterWhere([
            'status' => $this->status,
            'required_for_role' => $this->required_for_role,
            'is_required' => $this->is_required,
        ]);

        $query->andFilterWhere(['like', 'category_name', $this->category_name])
              ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

    /**
     * Search categories updated within last N days
     * 
     * @param array $params
     * @param int $days Number of days
     * @return ActiveDataProvider
     */
    public function searchRecentlyUpdated($params, $days = 30)
    {
        $date = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        $query = DocumentCategory::find()->where(['>=', 'updated_at', $date]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'updated_at' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Apply filters
        $query->andFilterWhere([
            'status' => $this->status,
            'required_for_role' => $this->required_for_role,
            'is_required' => $this->is_required,
        ]);

        $query->andFilterWhere(['like', 'category_name', $this->category_name])
              ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

    /**
     * Search categories by name pattern
     * 
     * @param array $params
     * @param string $pattern Search pattern
     * @return ActiveDataProvider
     */
    public function searchByNamePattern($params, $pattern)
    {
        $query = DocumentCategory::find()->where(['like', 'category_name', $pattern]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'category_name' => SORT_ASC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Apply other filters
        $query->andFilterWhere([
            'status' => $this->status,
            'required_for_role' => $this->required_for_role,
            'is_required' => $this->is_required,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

    /**
     * Search categories with documents count
     * Returns categories ordered by number of associated documents
     * 
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchWithDocumentCount($params)
    {
        $query = DocumentCategory::find()
            ->joinWith(['userDocuments'])
            ->groupBy(['document_categories.category_id'])
            ->select(['document_categories.*', 'COUNT(documents.document_id) as doc_count']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'doc_count' => SORT_DESC, // Most used categories first
                ],
                'attributes' => [
                    'category_id',
                    'category_name',
                    'required_for_role',
                    'is_required',
                    'status',
                    'created_at',
                    'doc_count' => [
                        'asc' => ['COUNT(documents.document_id)' => SORT_ASC],
                        'desc' => ['COUNT(documents.document_id)' => SORT_DESC],
                    ],
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Apply filters
        $query->andFilterWhere([
            'document_categories.status' => $this->status,
            'document_categories.required_for_role' => $this->required_for_role,
            'document_categories.is_required' => $this->is_required,
        ]);

        $query->andFilterWhere(['like', 'document_categories.category_name', $this->category_name])
              ->andFilterWhere(['like', 'document_categories.description', $this->description]);

        return $dataProvider;
    }

    /**
     * Search categories with no documents
     * 
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchUnused($params)
    {
        $query = DocumentCategory::find()
            ->leftJoin('documents', 'document_categories.category_id = documents.category_id')
            ->where(['documents.document_id' => null]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Apply filters
        $query->andFilterWhere([
            'document_categories.status' => $this->status,
            'document_categories.required_for_role' => $this->required_for_role,
            'document_categories.is_required' => $this->is_required,
        ]);

        $query->andFilterWhere(['like', 'document_categories.category_name', $this->category_name])
              ->andFilterWhere(['like', 'document_categories.description', $this->description]);

        return $dataProvider;
    }
}