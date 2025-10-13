<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PartnerDetails]].
 *
 * @see PartnerDetails
 */
class PartnerDetailsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return PartnerDetails[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PartnerDetails|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
