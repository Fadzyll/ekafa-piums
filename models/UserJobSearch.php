<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UserJob;

/**
 * UserJobSearch represents the model behind the search form of `app\models\UserJob`.
 */
class UserJobSearch extends UserJob
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userJob_id', 'user_id'], 'integer'],
            [['job', 'employer', 'employer_address', 'employer_phone_number'], 'safe'],
            [['gross_salary', 'net_salary'], 'number'],
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
        $query = UserJob::find();

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
            'userJob_id' => $this->userJob_id,
            'user_id' => $this->user_id,
            'gross_salary' => $this->gross_salary,
            'net_salary' => $this->net_salary,
        ]);

        $query->andFilterWhere(['like', 'job', $this->job])
            ->andFilterWhere(['like', 'employer', $this->employer])
            ->andFilterWhere(['like', 'employer_address', $this->employer_address])
            ->andFilterWhere(['like', 'employer_phone_number', $this->employer_phone_number]);

        return $dataProvider;
    }
}
