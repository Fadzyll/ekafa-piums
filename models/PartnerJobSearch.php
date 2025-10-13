<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PartnerJob;

/**
 * PartnerJobSearch represents the model behind the search form of `app\models\PartnerJob`.
 */
class PartnerJobSearch extends PartnerJob
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['partner_id'], 'integer'],
            [['partner_job', 'partner_employer', 'partner_employer_address', 'partner_employer_phone_number'], 'safe'],
            [['partner_gross_salary', 'partner_net_salary'], 'number'],
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
        $query = PartnerJob::find();

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
            'partner_id' => $this->partner_id,
            'partner_gross_salary' => $this->partner_gross_salary,
            'partner_net_salary' => $this->partner_net_salary,
        ]);

        $query->andFilterWhere(['like', 'partner_job', $this->partner_job])
            ->andFilterWhere(['like', 'partner_employer', $this->partner_employer])
            ->andFilterWhere(['like', 'partner_employer_address', $this->partner_employer_address])
            ->andFilterWhere(['like', 'partner_employer_phone_number', $this->partner_employer_phone_number]);

        return $dataProvider;
    }
}
