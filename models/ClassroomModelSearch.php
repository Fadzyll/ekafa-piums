<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ClassroomModel;

/**
 * ClassroomModelSearch represents the model behind the search form of `app\models\ClassroomModel`.
 * Updated to match E-Kafa Database Data Dictionary v1.0 - Table #4
 */
class ClassroomModelSearch extends ClassroomModel
{
    public $teacherName;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['class_id', 'user_id', 'quota', 'current_enrollment', 'year'], 'integer'],
            [['class_name', 'session_type', 'status', 'grade_level', 'classroom_location', 'teacherName', 'class_start_date', 'class_end_date'], 'safe'],
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
                    'grade_level',
                    'quota',
                    'current_enrollment',
                    'class_start_date',
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
            'class.user_id' => $this->user_id,
            'class.quota' => $this->quota,
            'class.current_enrollment' => $this->current_enrollment,
            'class.year' => $this->year,
        ]);

        $query->andFilterWhere(['like', 'class.class_name', $this->class_name])
            ->andFilterWhere(['like', 'class.session_type', $this->session_type])
            ->andFilterWhere(['like', 'class.status', $this->status])
            ->andFilterWhere(['like', 'class.grade_level', $this->grade_level])
            ->andFilterWhere(['like', 'class.classroom_location', $this->classroom_location])
            ->andFilterWhere(['like', 'users.username', $this->teacherName]);

        // Date filters
        if (!empty($this->class_start_date)) {
            $query->andFilterWhere(['>=', 'class.class_start_date', $this->class_start_date]);
        }
        if (!empty($this->class_end_date)) {
            $query->andFilterWhere(['<=', 'class.class_end_date', $this->class_end_date]);
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
            'year' => 'Year',
        ]);
    }
}