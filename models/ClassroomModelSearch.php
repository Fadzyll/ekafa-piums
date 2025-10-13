<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ClassroomModel;

/**
 * ClassroomModelSearch represents the model behind the search form of `app\models\ClassroomModel`.
 */
class ClassroomModelSearch extends ClassroomModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['class_id', 'year', 'user_id', 'quota', 'current_enrollment'], 'integer'],
            [['class_name', 'session_type', 'status'], 'safe'],
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
        $query = ClassroomModel::find();

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
            'class_id' => $this->class_id,
            'year' => $this->year,
            'user_id' => $this->user_id,
            'quota' => $this->quota,
            'current_enrollment' => $this->current_enrollment,
        ]);

        $query->andFilterWhere(['like', 'class_name', $this->class_name])
            ->andFilterWhere(['like', 'session_type', $this->session_type])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
