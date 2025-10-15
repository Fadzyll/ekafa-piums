<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UserDocuments]].
 *
 * @see UserDocuments
 */
class UserDocumentsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return UserDocuments[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return UserDocuments|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
