<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\UserJob $model */

$this->title = 'My Job Information';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-secondary text-white">
            <h3 class="mb-0"><?= Html::encode($this->title) ?></h3>
        </div>

        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'job')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'employer')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'employer_address')->textarea(['rows' => 3]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'employer_phone_number')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'gross_salary')->textInput(['type' => 'number', 'min' => 0]) ?>
                    <?= $form->field($model, 'net_salary')->textInput(['type' => 'number', 'min' => 0]) ?>
                </div>
            </div>

            <div class="form-group mt-4 text-end">
                <?= Html::a('Back to Profile', ['user-details/view'], ['class' => 'btn btn-primary']) ?>
                <?= Html::submitButton('Save Job Info', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>