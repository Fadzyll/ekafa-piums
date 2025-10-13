<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ClassroomModel]].
 *
 * @see ClassroomModel
 */
class ClassQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ClassroomModel[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ClassroomModel|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
