<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UserJobDetails]].
 *
 * @see UserJobDetails
 */
class UserJobDetailsQuery extends \yii\db\ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return UserJobDetails[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return UserJobDetails|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}