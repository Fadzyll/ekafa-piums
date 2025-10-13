<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PartnerJob]].
 *
 * @see PartnerJob
 */
class PartnerJobQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return PartnerJob[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PartnerJob|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
