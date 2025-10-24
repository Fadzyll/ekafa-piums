<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\ClassroomModel;

/** @var yii\web\View $this */
/** @var app\models\ClassroomModel $model */
/** @var array $teachers */
?>

<style>
.form-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px 20px 0 0;
    padding: 2.5rem;
    color: white;
    margin-bottom: 0;
}

.form-title {
    font-size: 2rem;
    font-weight: 800;
    margin: 0 0 0.5rem 0;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.form-subtitle {
    opacity: 0.9;
    font-size: 0.95rem;
    margin: 0;
}

.form-container {
    padding: 2.5rem;
}

.form-section {
    background: #f9fafb;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 1.5rem;
    border: 2px solid #e5e7eb;
    transition: all 0.3s ease;
}

.form-section:hover {
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
}

.section-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #e5e7eb;
}

.section-title i {
    color: #667eea;
    font-size: 1.25rem;
}

.form-label {
    font-weight: 700;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.9375rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-label i {
    color: #667eea;
}

.form-control, .form-select {
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 0.875rem 1.25rem;
    font-size: 0.9375rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    outline: none;
}

.form-control::placeholder {
    color: #9ca3af;
    font-style: italic;
}

.input-helper {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.5rem;
    color: #6b7280;
    font-size: 0.8125rem;
}

.input-helper i {
    color: #667eea;
}

.button-group {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-top: 2rem;
}

.btn-form {
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 1rem;
    border: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-cancel {
    background: #e5e7eb;
    color: #374151;
}

.btn-cancel:hover {
    background: #d1d5db;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.btn-save {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
}

.status-selector {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.status-option {
    position: relative;
}

.status-option input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.status-option label {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1.25rem 1rem;
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    min-height: 90px;
}

.status-option label i {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
    color: #6b7280;
    transition: all 0.3s ease;
}

.status-option label span {
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.status-option.selected label {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
}

.status-option.selected label i,
.status-option.selected label span {
    color: white;
}

.invalid-feedback {
    display: block;
    color: #ef4444;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    font-weight: 600;
}

.has-error .form-control,
.has-error .form-select {
    border-color: #ef4444;
}

.has-error .form-label {
    color: #ef4444;
}

.info-alert {
    background: linear-gradient(135deg, #e0e7ff 0%, #dbeafe 100%);
    border: 2px solid #93c5fd;
    border-radius: 12px;
    padding: 1rem 1.5rem;
    margin-top: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.info-alert i {
    font-size: 1.5rem;
    color: #3b82f6;
}

.info-alert-content {
    flex: 1;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.form-section {
    animation: slideInUp 0.5s ease;
}

.form-section:nth-child(1) { animation-delay: 0.1s; }
.form-section:nth-child(2) { animation-delay: 0.2s; }
.form-section:nth-child(3) { animation-delay: 0.3s; }
.form-section:nth-child(4) { animation-delay: 0.4s; }

@media (max-width: 768px) {
    .button-group {
        grid-template-columns: 1fr;
    }

    .status-selector {
        grid-template-columns: 1fr;
    }

    .form-container {
        padding: 1.5rem;
    }
}
</style>

<div class="card shadow-lg border-0">
    <div class="form-header">
        <div class="form-title">
            <i class="bi bi-<?= $model->isNewRecord ? 'plus-circle-fill' : 'pencil-square' ?>"></i>
            <?= Html::encode($this->title) ?>
        </div>
        <p class="form-subtitle">
            <?= $model->isNewRecord
                ? 'Create a new classroom for your institution'
                : 'Update the information for this classroom' ?>
        </p>
    </div>

    <div class="form-container">
        <?php $form = ActiveForm::begin([
            'id' => 'classroom-form',
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
            'validateOnSubmit' => true,
            'options' => ['class' => 'classroom-form'],
        ]); ?>

        <!-- Basic Information Section -->
        <div class="form-section">
            <div class="section-title">
                <i class="bi bi-info-circle-fill"></i>
                Basic Information
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <?= $form->field($model, 'class_name')->textInput([
                        'maxlength' => true,
                        'placeholder' => 'e.g., Al-Quran Reading Class 1A',
                        'class' => 'form-control',
                    ])->label('<i class="bi bi-door-open"></i> Class Name', ['class' => 'form-label']) ?>
                    <div class="input-helper">
                        <i class="bi bi-lightbulb"></i>
                        <span>Enter a descriptive name for the classroom</span>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <?= $form->field($model, 'year')->textInput([
                        'type' => 'number',
                        'min' => 2020,
                        'max' => 2050,
                        'value' => $model->isNewRecord ? date('Y') : $model->year,
                        'class' => 'form-control',
                    ])->label('<i class="bi bi-calendar"></i> Year', ['class' => 'form-label']) ?>
                </div>

                <div class="col-md-3 mb-4">
                    <?= $form->field($model, 'grade_level')->dropDownList(
                        ClassroomModel::optsGradeLevel(),
                        ['prompt' => 'Select Grade', 'class' => 'form-select']
                    )->label('<i class="bi bi-mortarboard"></i> Grade Level', ['class' => 'form-label']) ?>
                </div>
            </div>
        </div>

        <!-- Session & Schedule Section -->
        <div class="form-section">
            <div class="section-title">
                <i class="bi bi-calendar-event-fill"></i>
                Session & Schedule
            </div>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <?= $form->field($model, 'session_type')->dropDownList(
                        ClassroomModel::optsSessionType(),
                        ['prompt' => 'Select Session', 'class' => 'form-select']
                    )->label('<i class="bi bi-clock"></i> Session Type', ['class' => 'form-label']) ?>
                </div>

                <div class="col-md-4 mb-4">
                    <?= $form->field($model, 'class_start_date')->input('date', [
                        'class' => 'form-control'
                    ])->label('<i class="bi bi-calendar-check"></i> Start Date', ['class' => 'form-label']) ?>
                </div>

                <div class="col-md-4 mb-4">
                    <?= $form->field($model, 'class_end_date')->input('date', [
                        'class' => 'form-control'
                    ])->label('<i class="bi bi-calendar-x"></i> End Date', ['class' => 'form-label']) ?>
                </div>
            </div>
        </div>

        <!-- Teacher & Location Section -->
        <div class="form-section">
            <div class="section-title">
                <i class="bi bi-people-fill"></i>
                Teacher & Location
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <?= $form->field($model, 'user_id')->dropDownList(
                        $teachers,
                        ['prompt' => 'Select Teacher', 'class' => 'form-select']
                    )->label('<i class="bi bi-person-fill"></i> Assigned Teacher', ['class' => 'form-label']) ?>
                    <div class="input-helper">
                        <i class="bi bi-lightbulb"></i>
                        <span>Choose the teacher responsible for this classroom</span>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <?= $form->field($model, 'classroom_location')->textInput([
                        'maxlength' => true,
                        'placeholder' => 'e.g., Building A, Room 101',
                        'class' => 'form-control'
                    ])->label('<i class="bi bi-geo-alt"></i> Classroom Location', ['class' => 'form-label']) ?>
                    <div class="input-helper">
                        <i class="bi bi-lightbulb"></i>
                        <span>Specify the physical location of the classroom</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enrollment Settings Section -->
        <div class="form-section">
            <div class="section-title">
                <i class="bi bi-diagram-3-fill"></i>
                Enrollment & Status
            </div>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <?= $form->field($model, 'quota')->textInput([
                        'type' => 'number',
                        'min' => 1,
                        'placeholder' => 'e.g., 30',
                        'class' => 'form-control'
                    ])->label('<i class="bi bi-people"></i> Student Quota', ['class' => 'form-label']) ?>
                    <div class="input-helper">
                        <i class="bi bi-lightbulb"></i>
                        <span>Maximum number of students allowed</span>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <?= $form->field($model, 'current_enrollment')->textInput([
                        'type' => 'number',
                        'min' => 0,
                        'readonly' => !$model->isNewRecord,
                        'class' => 'form-control',
                        'value' => $model->isNewRecord ? 0 : $model->current_enrollment
                    ])->label('<i class="bi bi-person-check"></i> Current Enrollment', ['class' => 'form-label']) ?>
                    <?php if (!$model->isNewRecord): ?>
                    <div class="input-helper">
                        <i class="bi bi-info-circle"></i>
                        <span>This field is automatically managed</span>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4 mb-4">
                    <?= $form->field($model, 'status')->dropDownList(
                        ClassroomModel::optsStatus(),
                        ['prompt' => 'Select Status', 'class' => 'form-select']
                    )->label('<i class="bi bi-toggle-on"></i> Status', ['class' => 'form-label']) ?>
                </div>
            </div>

            <?php if (!$model->isNewRecord && $model->quota > 0): ?>
            <div class="info-alert">
                <i class="bi bi-graph-up"></i>
                <div class="info-alert-content">
                    <strong>Enrollment Status:</strong>
                    <?= $model->current_enrollment ?> / <?= $model->quota ?> students enrolled
                    (<?= number_format($model->getEnrollmentPercentage(), 1) ?>% full)
                    <?php if ($model->isFull()): ?>
                        <span class="badge bg-danger ms-2">CLASS FULL</span>
                    <?php else: ?>
                        <span class="badge bg-success ms-2"><?= $model->getAvailableSlots() ?> SLOTS AVAILABLE</span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Form Actions -->
        <div class="button-group">
            <?= Html::a('<i class="bi bi-x-circle"></i> Cancel', ['index'], [
                'class' => 'btn btn-form btn-cancel'
            ]) ?>
            <?= Html::submitButton(
                $model->isNewRecord
                    ? '<i class="bi bi-check-circle"></i> Create Classroom'
                    : '<i class="bi bi-save"></i> Save Changes',
                ['class' => 'btn btn-form btn-save']
            ) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$script = <<< JS
// Date validation
$('#classroommodel-class_start_date, #classroommodel-class_end_date').on('change', function() {
    var startDate = $('#classroommodel-class_start_date').val();
    var endDate = $('#classroommodel-class_end_date').val();

    if (startDate && endDate && startDate > endDate) {
        alert('Class start date cannot be after end date');
        $('#classroommodel-class_end_date').val('');
    }
});

// Quota validation
$('#classroommodel-current_enrollment').on('change', function() {
    var quota = parseInt($('#classroommodel-quota').val());
    var current = parseInt($(this).val());

    if (current > quota) {
        alert('Current enrollment cannot exceed quota');
        $(this).val(quota);
    }
});

// Form validation feedback
$('#classroom-form').on('beforeValidate', function() {
    $('.has-error').removeClass('has-error');
});

$('#classroom-form').on('afterValidate', function(event, messages, errorAttributes) {
    $.each(errorAttributes, function(index, attribute) {
        $('#' + attribute.id).closest('.mb-4').addClass('has-error');
    });
});

// Smooth scroll to first error
$('#classroom-form').on('afterValidate', function(event, messages, errorAttributes) {
    if (errorAttributes.length > 0) {
        $('html, body').animate({
            scrollTop: $('#' + errorAttributes[0].id).offset().top - 100
        }, 300);
    }
});
JS;
$this->registerJs($script);
?>
