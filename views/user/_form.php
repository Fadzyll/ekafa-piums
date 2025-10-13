<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Users $model */
/** @var bool $isRestricted */

?>

<div class="card shadow" style="min-height: auto;">
    <div class="card-header bg-primary text-white">
        <h3 class="mb-0"><?= Html::encode($this->title) ?></h3>
    </div>

    <div class="card-body py-3">
        <?php $form = ActiveForm::begin([
            'id' => 'user-form',
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'form-label text-start d-block'],
                'inputOptions' => ['class' => 'form-control'],
                'errorOptions' => ['class' => 'invalid-feedback d-block'],
            ],
        ]); ?>

        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

        <?php if ($isRestricted): ?>
            <?= $form->field($model, 'email')->textInput([
                'readonly' => true,
                'class' => 'form-control'
            ]) ?>
        <?php else: ?>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        <?php endif; ?>

        <?php if (!$isRestricted): ?>
            <?= $form->field($model, 'password', [
                'template' => '{label}
                    <div class="input-group">
                        {input}
                        <button class="btn btn-outline-secondary toggle-password" type="button">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    {error}',
            ])->passwordInput(['class' => 'form-control', 'id' => 'password']) ?>
        <?php else: ?>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" value="********" disabled>
                    <button class="btn btn-outline-secondary" type="button" disabled>
                        <i class="bi bi-lock-fill"></i>
                    </button>
                </div>
                <div class="form-text text-muted">
                    Password changes are disabled for <strong>Teacher</strong> and <strong>Parent</strong> roles.
                </div>
            </div>
        <?php endif; ?>

        <?php if ($isRestricted): ?>
            <?= $form->field($model, 'role')->dropDownList([
                $model->role => $model->role,
            ], ['disabled' => true]) ?>
        <?php else: ?>
            <?= $form->field($model, 'role')->dropDownList([
                'Admin' => 'Admin',
                'Teacher' => 'Teacher',
                'Parent' => 'Parent',
            ], ['prompt' => 'Select Role']) ?>
        <?php endif; ?>

        <div class="form-group mt-4">
            <div class="row g-2">
                <div class="col-6">
                    <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary w-100']) ?>
                </div>
                <div class="col-6">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success w-100']) ?>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
// JS for password toggle
$this->registerJs(<<<JS
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function () {
            const input = this.closest('.input-group').querySelector('input');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
JS);

// CSS to hide native browser password icons
$this->registerCss(<<<CSS
input[type="password"]::-ms-reveal,
input[type="password"]::-ms-clear {
    display: none;
}
input[type="password"]::-webkit-credentials-auto-fill-button {
    visibility: hidden;
    display: none !important;
    pointer-events: none;
    position: absolute;
    right: 0;
}
input[type="password"]::-webkit-inner-spin-button,
input[type="password"]::-webkit-outer-spin-button,
input[type="password"]::-webkit-clear-button {
    display: none;
}
CSS);
?>