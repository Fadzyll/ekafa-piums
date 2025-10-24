<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UserDocuments;

/**
 * UserDocumentsSearch represents the model behind the search form of `app\models\UserDocuments`.
 */
class UserDocumentsSearch extends UserDocuments
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['document_id', 'user_id', 'uploaded_by', 'verified_by', 'version', 'is_latest_version', 'file_size', 'category_id', 'owner_id'], 'integer'],
            [['document_type', 'document_name', 'original_filename', 'file_url', 'mime_type', 'file_hash', 'status', 'upload_date', 'updated_at', 'expiry_date', 'verified_at', 'admin_notes', 'rejection_reason', 'owner_type'], 'safe'],
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
        $query = UserDocuments::find();

        // add conditions that should always apply here
        // For example, exclude deleted documents by default
        // $query->andWhere(['!=', 'status', UserDocuments::STATUS_DELETED]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'upload_date' => SORT_DESC,
                ],
                'attributes' => [
                    'document_id',
                    'user_id',
                    'uploaded_by',
                    'verified_by',
                    'document_type',
                    'document_name',
                    'version',
                    'status',
                    'upload_date',
                    'updated_at',
                    'expiry_date',
                    'verified_at',
                    'category_id',
                    'owner_type',
                    'owner_id',
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
            'document_id' => $this->document_id,
            'user_id' => $this->user_id,
            'uploaded_by' => $this->uploaded_by,
            'verified_by' => $this->verified_by,
            'version' => $this->version,
            'is_latest_version' => $this->is_latest_version,
            'file_size' => $this->file_size,
            'category_id' => $this->category_id,
            'owner_id' => $this->owner_id,
            'status' => $this->status, // Exact match for status (dropdown)
            'owner_type' => $this->owner_type, // Exact match for owner_type
        ]);

        // Use LIKE for text searches
        $query->andFilterWhere(['like', 'document_type', $this->document_type])
            ->andFilterWhere(['like', 'document_name', $this->document_name])
            ->andFilterWhere(['like', 'original_filename', $this->original_filename])
            ->andFilterWhere(['like', 'file_url', $this->file_url])
            ->andFilterWhere(['like', 'mime_type', $this->mime_type])
            ->andFilterWhere(['like', 'file_hash', $this->file_hash])
            ->andFilterWhere(['like', 'admin_notes', $this->admin_notes])
            ->andFilterWhere(['like', 'rejection_reason', $this->rejection_reason]);

        // Handle upload_date filter properly (search by specific date)
        if (!empty($this->upload_date)) {
            // If it's just a date (no time), search the entire day
            if (strlen($this->upload_date) == 10) { // Format: YYYY-MM-DD
                $query->andFilterWhere(['>=', 'upload_date', $this->upload_date . ' 00:00:00'])
                      ->andFilterWhere(['<=', 'upload_date', $this->upload_date . ' 23:59:59']);
            } else {
                // If it includes time, search exactly
                $query->andFilterWhere(['upload_date' => $this->upload_date]);
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

        // Handle expiry_date filter (search by specific date)
        if (!empty($this->expiry_date)) {
            if (strlen($this->expiry_date) == 10) { // Format: YYYY-MM-DD
                $query->andFilterWhere(['>=', 'expiry_date', $this->expiry_date . ' 00:00:00'])
                      ->andFilterWhere(['<=', 'expiry_date', $this->expiry_date . ' 23:59:59']);
            } else {
                $query->andFilterWhere(['expiry_date' => $this->expiry_date]);
            }
        }

        // Handle verified_at filter (search by specific date)
        if (!empty($this->verified_at)) {
            if (strlen($this->verified_at) == 10) { // Format: YYYY-MM-DD
                $query->andFilterWhere(['>=', 'verified_at', $this->verified_at . ' 00:00:00'])
                      ->andFilterWhere(['<=', 'verified_at', $this->verified_at . ' 23:59:59']);
            } else {
                $query->andFilterWhere(['verified_at' => $this->verified_at]);
            }
        }

        return $dataProvider;
    }

    /**
     * Search only approved documents
     * 
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchApproved($params)
    {
        $query = UserDocuments::find()->where(['status' => UserDocuments::STATUS_APPROVED]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'upload_date' => SORT_DESC,
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
            'category_id' => $this->category_id,
            'owner_type' => $this->owner_type,
        ]);

        $query->andFilterWhere(['like', 'document_name', $this->document_name])
              ->andFilterWhere(['like', 'document_type', $this->document_type]);

        return $dataProvider;
    }

    /**
     * Search pending review documents
     * 
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchPendingReview($params)
    {
        $query = UserDocuments::find()->where(['status' => UserDocuments::STATUS_PENDING_REVIEW]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'upload_date' => SORT_ASC, // Oldest first for pending review
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
            'category_id' => $this->category_id,
        ]);

        $query->andFilterWhere(['like', 'document_name', $this->document_name])
              ->andFilterWhere(['like', 'document_type', $this->document_type]);

        return $dataProvider;
    }

    /**
     * Search rejected documents
     * 
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchRejected($params)
    {
        $query = UserDocuments::find()->where(['status' => UserDocuments::STATUS_REJECTED]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'verified_at' => SORT_DESC,
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
            'category_id' => $this->category_id,
        ]);

        $query->andFilterWhere(['like', 'document_name', $this->document_name])
              ->andFilterWhere(['like', 'document_type', $this->document_type])
              ->andFilterWhere(['like', 'rejection_reason', $this->rejection_reason]);

        return $dataProvider;
    }

    /**
     * Search documents by status
     * 
     * @param array $params
     * @param string $status Status to filter by
     * @return ActiveDataProvider
     */
    public function searchByStatus($params, $status)
    {
        $query = UserDocuments::find()->where(['status' => $status]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'upload_date' => SORT_DESC,
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
            'category_id' => $this->category_id,
            'owner_type' => $this->owner_type,
        ]);

        $query->andFilterWhere(['like', 'document_name', $this->document_name])
              ->andFilterWhere(['like', 'document_type', $this->document_type]);

        return $dataProvider;
    }

    /**
     * Search documents by user
     * 
     * @param array $params
     * @param int $userId User ID to filter by
     * @return ActiveDataProvider
     */
    public function searchByUser($params, $userId)
    {
        $query = UserDocuments::find()->where(['user_id' => $userId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'upload_date' => SORT_DESC,
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
            'category_id' => $this->category_id,
        ]);

        $query->andFilterWhere(['like', 'document_name', $this->document_name])
              ->andFilterWhere(['like', 'document_type', $this->document_type]);

        return $dataProvider;
    }

    /**
     * Search documents by category
     * 
     * @param array $params
     * @param int $categoryId Category ID to filter by
     * @return ActiveDataProvider
     */
    public function searchByCategory($params, $categoryId)
    {
        $query = UserDocuments::find()->where(['category_id' => $categoryId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'upload_date' => SORT_DESC,
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

        $query->andFilterWhere(['like', 'document_name', $this->document_name])
              ->andFilterWhere(['like', 'document_type', $this->document_type]);

        return $dataProvider;
    }

    /**
     * Search documents uploaded within last N days
     * 
     * @param array $params
     * @param int $days Number of days to look back
     * @return ActiveDataProvider
     */
    public function searchRecentlyUploaded($params, $days = 30)
    {
        $date = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        $query = UserDocuments::find()->where(['>=', 'upload_date', $date]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'upload_date' => SORT_DESC,
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
            'status' => $this->status,
            'category_id' => $this->category_id,
        ]);

        $query->andFilterWhere(['like', 'document_name', $this->document_name])
              ->andFilterWhere(['like', 'document_type', $this->document_type]);

        return $dataProvider;
    }

    /**
     * Search expired documents
     * 
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchExpired($params)
    {
        $today = date('Y-m-d H:i:s');
        $query = UserDocuments::find()
            ->where(['<', 'expiry_date', $today])
            ->andWhere(['not', ['expiry_date' => null]]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'expiry_date' => SORT_DESC,
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
            'category_id' => $this->category_id,
        ]);

        $query->andFilterWhere(['like', 'document_name', $this->document_name])
              ->andFilterWhere(['like', 'document_type', $this->document_type]);

        return $dataProvider;
    }

    /**
     * Search documents expiring soon (within next N days)
     * 
     * @param array $params
     * @param int $days Number of days to look ahead
     * @return ActiveDataProvider
     */
    public function searchExpiringSoon($params, $days = 30)
    {
        $today = date('Y-m-d H:i:s');
        $futureDate = date('Y-m-d H:i:s', strtotime("+{$days} days"));
        
        $query = UserDocuments::find()
            ->where(['between', 'expiry_date', $today, $futureDate])
            ->andWhere(['not', ['expiry_date' => null]]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'expiry_date' => SORT_ASC, // Soonest expiring first
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
            'status' => $this->status,
            'category_id' => $this->category_id,
        ]);

        $query->andFilterWhere(['like', 'document_name', $this->document_name])
              ->andFilterWhere(['like', 'document_type', $this->document_type]);

        return $dataProvider;
    }

    /**
     * Search only latest versions of documents
     * 
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchLatestVersions($params)
    {
        $query = UserDocuments::find()->where(['is_latest_version' => 1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'upload_date' => SORT_DESC,
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
            'status' => $this->status,
            'category_id' => $this->category_id,
        ]);

        $query->andFilterWhere(['like', 'document_name', $this->document_name])
              ->andFilterWhere(['like', 'document_type', $this->document_type]);

        return $dataProvider;
    }

    /**
     * Search documents by owner type
     * 
     * @param array $params
     * @param string $ownerType Owner type to filter by
     * @return ActiveDataProvider
     */
    public function searchByOwnerType($params, $ownerType)
    {
        $query = UserDocuments::find()->where(['owner_type' => $ownerType]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'upload_date' => SORT_DESC,
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
            'category_id' => $this->category_id,
        ]);

        $query->andFilterWhere(['like', 'document_name', $this->document_name])
              ->andFilterWhere(['like', 'document_type', $this->document_type]);

        return $dataProvider;
    }

    /**
     * Search documents verified by specific admin
     * 
     * @param array $params
     * @param int $verifiedBy User ID of admin who verified
     * @return ActiveDataProvider
     */
    public function searchVerifiedBy($params, $verifiedBy)
    {
        $query = UserDocuments::find()->where(['verified_by' => $verifiedBy]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'verified_at' => SORT_DESC,
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
            'status' => $this->status,
            'category_id' => $this->category_id,
        ]);

        $query->andFilterWhere(['like', 'document_name', $this->document_name])
              ->andFilterWhere(['like', 'document_type', $this->document_type]);

        return $dataProvider;
    }
}