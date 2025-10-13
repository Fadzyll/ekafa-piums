<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PartnerJob $model */

$this->title = 'Create Partner Job';
$this->params['breadcrumbs'][] = ['label' => 'Partner Jobs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partner-job-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
