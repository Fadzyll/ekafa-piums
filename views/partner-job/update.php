<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PartnerJob $model */

$this->title = 'Update Partner Job: ' . $model->partner_id;
$this->params['breadcrumbs'][] = ['label' => 'Partner Jobs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->partner_id, 'url' => ['view', 'partner_id' => $model->partner_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="partner-job-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
