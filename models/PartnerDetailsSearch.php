<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PartnerDetails;

/**
 * PartnerDetailsSearch represents the model behind the search form of `app\models\PartnerDetails`.
 */
class PartnerDetailsSearch extends PartnerDetails
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['partner_id'], 'integer'],
            [['partner_name', 'partner_ic_number', 'partner_phone_number', 'partner_citizenship', 'partner_marital_status', 'partner_address', 'partner_city', 'partner_postcode', 'partner_state'], 'safe'],
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
        $query = PartnerDetails::find();

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
        ]);

        $query->andFilterWhere(['like', 'partner_name', $this->partner_name])
            ->andFilterWhere(['like', 'partner_ic_number', $this->partner_ic_number])
            ->andFilterWhere(['like', 'partner_phone_number', $this->partner_phone_number])
            ->andFilterWhere(['like', 'partner_citizenship', $this->partner_citizenship])
            ->andFilterWhere(['like', 'partner_marital_status', $this->partner_marital_status])
            ->andFilterWhere(['like', 'partner_address', $this->partner_address])
            ->andFilterWhere(['like', 'partner_city', $this->partner_city])
            ->andFilterWhere(['like', 'partner_postcode', $this->partner_postcode])
            ->andFilterWhere(['like', 'partner_state', $this->partner_state]);

        return $dataProvider;
    }
}
