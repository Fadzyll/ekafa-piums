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
            [['category_name', 'description', 'required_for_role', 'status', 'created_at'], 'safe'],
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
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = DocumentCategory::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'category_id' => $this->category_id,
            'is_required' => $this->is_required,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'category_name', $this->category_name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'required_for_role', $this->required_for_role])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}