<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\UserDocuments $model */

$this->title = Yii::t('app', 'Create User Documents');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Documents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-documents-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
