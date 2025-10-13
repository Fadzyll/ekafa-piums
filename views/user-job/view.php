<?php
use yii\helpers\Html;

$this->title = 'My Job Details';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0"><?= Html::encode($this->title) ?></h3>
            <?= Html::a('Update Job Details', ['profile'], ['class' => 'btn btn-light']) ?>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Job Title:</strong> <?= Html::encode($model->job) ?></p>
                    <p><strong>Employer:</strong> <?= Html::encode($model->employer) ?></p>
                    <p><strong>Employer Address:</strong> <?= Html::encode($model->employer_address) ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Employer Phone:</strong> <?= Html::encode($model->employer_phone_number) ?></p>
                    <p><strong>Gross Salary:</strong> <?= Html::encode($model->gross_salary) ?></p>
                    <p><strong>Net Salary:</strong> <?= Html::encode($model->net_salary) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>