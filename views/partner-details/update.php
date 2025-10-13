<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PartnerDetails $model */

$this->title = 'Update Partner Details: ' . $model->partner_id;
$this->params['breadcrumbs'][] = ['label' => 'Partner Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->partner_id, 'url' => ['view', 'partner_id' => $model->partner_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="partner-details-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
