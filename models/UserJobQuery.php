<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UserJob]].
 *
 * @see UserJob
 */
class UserJobQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return UserJob[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return UserJob|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
