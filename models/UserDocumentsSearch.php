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
            [['document_id', 'user_id'], 'integer'],
            [['document_type', 'file_url', 'status', 'upload_date'], 'safe'],
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

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'upload_date' => $this->upload_date,
        ]);

        $query->andFilterWhere(['like', 'document_type', $this->document_type])
            ->andFilterWhere(['like', 'file_url', $this->file_url])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
