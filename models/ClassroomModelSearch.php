<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ClassroomModel;

/**
 * ClassroomModelSearch represents the model behind the search form of `app\models\ClassroomModel`.
 * Enhanced with additional search filters and sorting options.
 */
class ClassroomModelSearch extends ClassroomModel
{
    public $teacherName;
    public $dateFrom;
    public $dateTo;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['class_id', 'year', 'user_id', 'quota', 'current_enrollment', 'min_age', 'max_age'], 'integer'],
            [['class_name', 'session_type', 'status', 'grade_level', 'teacherName', 'dateFrom', 'dateTo', 'building'], 'safe'],
            [['monthly_fee', 'registration_fee'], 'number'],
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

        // Join with Users table for teacher search
        $query->joinWith(['user']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
                'attributes' => [
                    'class_id',
                    'class_name',
                    'year',
                    'session_type',
                    'status',
                    'quota',
                    'current_enrollment',
                    'created_at',
                    'teacherName' => [
                        'asc' => ['users.username' => SORT_ASC],
                        'desc' => ['users.username' => SORT_DESC],
                    ],
                ],
            ],
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // Grid filtering conditions
        $query->andFilterWhere([
            'class.class_id' => $this->class_id,
            'class.year' => $this->year,
            'class.user_id' => $this->user_id,
            'class.quota' => $this->quota,
            'class.current_enrollment' => $this->current_enrollment,
            'class.min_age' => $this->min_age,
            'class.max_age' => $this->max_age,
        ]);

        $query->andFilterWhere(['like', 'class.class_name', $this->class_name])
            ->andFilterWhere(['like', 'class.session_type', $this->session_type])
            ->andFilterWhere(['like', 'class.status', $this->status])
            ->andFilterWhere(['like', 'class.grade_level', $this->grade_level])
            ->andFilterWhere(['like', 'class.building', $this->building])
            ->andFilterWhere(['like', 'users.username', $this->teacherName]);

        // Date range filter
        if (!empty($this->dateFrom)) {
            $query->andFilterWhere(['>=', 'class.class_start_date', $this->dateFrom]);
        }
        if (!empty($this->dateTo)) {
            $query->andFilterWhere(['<=', 'class.class_end_date', $this->dateTo]);
        }

        // Fee range filters
        if (!empty($this->monthly_fee)) {
            $query->andFilterWhere(['<=', 'class.monthly_fee', $this->monthly_fee]);
        }

        return $dataProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'teacherName' => 'Teacher Name',
            'dateFrom' => 'Start Date From',
            'dateTo' => 'Start Date To',
        ]);
    }
}