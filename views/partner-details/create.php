<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PartnerDetails $model */

$this->title = 'Create Partner Details';
$this->params['breadcrumbs'][] = ['label' => 'Partner Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partner-details-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
