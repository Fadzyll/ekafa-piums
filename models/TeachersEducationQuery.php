<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TeachersEducation]].
 *
 * @see TeachersEducation
 */
class TeachersEducationQuery extends \yii\db\ActiveQuery
{
    /**
     * Filter by user
     */
    public function byUser($userId)
    {
        return $this->andWhere(['user_id' => $userId]);
    }

    /**
     * Filter by degree level
     */
    public function byDegreeLevel($degreeLevel)
    {
        return $this->andWhere(['degree_level' => $degreeLevel]);
    }

    /**
     * Order by graduation date descending (most recent first)
     */
    public function latest()
    {
        return $this->orderBy(['graduation_date' => SORT_DESC]);
    }

    /**
     * {@inheritdoc}
     * @return TeachersEducation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TeachersEducation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}