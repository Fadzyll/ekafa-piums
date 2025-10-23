<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\ClassroomModel;

/** @var yii\web\View $this */
/** @var app\models\ClassroomModel $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $teachers */

// Register CSS
$this->registerCss("
    .form-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 0 30px rgba(0,0,0,0.1);
    }
    .form-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px 15px 0 0;
    }
    .form-header h3 {
        margin: 0;
        font-weight: 700;
    }
    .form-body {
        padding: 2rem;
    }
    .form-section {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
    }
    .section-title {
        font-weight: 600;
        color: #495057;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #dee2e6;
    }
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    .helper-text {
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }
    .btn-save {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 0.75rem 2rem;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }
");

// Register JS for validations
$this->registerJs("
    // Quota validation
    $('#classroommodel-current_enrollment').on('change', function() {
        var quota = parseInt($('#classroommodel-quota').val());
        var current = parseInt($(this).val());
        
        if (current > quota) {
            alert('Current enrollment cannot exceed quota');
            $(this).val(quota);
        }
    });

    // Date validation
    $('#classroommodel-class_start_date, #classroommodel-class_end_date').on('change', function() {
        var startDate = $('#classroommodel-class_start_date').val();
        var endDate = $('#classroommodel-class_end_date').val();
        
        if (startDate && endDate && startDate > endDate) {
            alert('Class start date cannot be after end date');
            $('#classroommodel-class_end_date').val('');
        }
    });
");
?>

<div class="form-container">
    <div class="form-header">
        <h3>
            <i class="fas fa-school me-2"></i>
            <?= Html::encode($this->title ?? 'Classroom Form') ?>
        </h3>
    </div>

    <div class="form-body">
        <?php $form = ActiveForm::begin([
            'id' => 'classroom-form',
            'enableAjaxValidation' => true,
            'options' => ['class' => 'needs-validation'],
        ]); ?>

        <!-- Basic Information Section -->
        <div class="form-section">
            <h5 class="section-title">
                <i class="fas fa-info-circle me-2"></i>Basic Information
            </h5>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'class_name')->textInput([
                        'maxlength' => true,
                        'placeholder' => 'e.g., Al-Quran Reading Class',
                        'class' => 'form-control'
                    ])->hint('Enter a descriptive name for the classroom') ?>
                </div>

                <div class="col-md-3">
                    <?= $form->field($model, 'year')->textInput([
                        'type' => 'number',
                        'min' => 2020,
                        'max' => 2050,
                        'value' => $model->isNewRecord ? date('Y') : $model->year,
                        'class' => 'form-control'
                    ]) ?>
                </div>

                <div class="col-md-3">
                    <?= $form->field($model, 'grade_level')->dropDownList(
                        ClassroomModel::optsGradeLevel(),
                        ['prompt' => 'Select Grade Level', 'class' => 'form-select']
                    ) ?>
                </div>
            </div>
        </div>

        <!-- Session & Schedule Section -->
        <div class="form-section">
            <h5 class="section-title">
                <i class="fas fa-calendar-alt me-2"></i>Session & Schedule
            </h5>
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'session_type')->dropDownList(
                        ClassroomModel::optsSessionType(),
                        ['prompt' => 'Select Session', 'class' => 'form-select']
                    ) ?>
                </div>

                <div class="col-md-4">
                    <?= $form->field($model, 'class_start_date')->input('date', [
                        'class' => 'form-control'
                    ]) ?>
                </div>

                <div class="col-md-4">
                    <?= $form->field($model, 'class_end_date')->input('date', [
                        'class' => 'form-control'
                    ]) ?>
                </div>
            </div>
        </div>

        <!-- Teacher & Location Section -->
        <div class="form-section">
            <h5 class="section-title">
                <i class="fas fa-chalkboard-teacher me-2"></i>Teacher & Location
            </h5>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'user_id')->dropDownList(
                        $teachers,
                        ['prompt' => 'Select Teacher', 'class' => 'form-select']
                    )->label('Teacher') ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'classroom_location')->textInput([
                        'maxlength' => true,
                        'placeholder' => 'e.g., Building A, Room 101',
                        'class' => 'form-control'
                    ])->hint('Enter the classroom location') ?>
                </div>
            </div>
        </div>

        <!-- Enrollment Settings Section -->
        <div class="form-section">
            <h5 class="section-title">
                <i class="fas fa-users me-2"></i>Enrollment Settings
            </h5>
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'quota')->textInput([
                        'type' => 'number',
                        'min' => 1,
                        'class' => 'form-control'
                    ])->hint('Maximum number of students') ?>
                </div>

                <div class="col-md-4">
                    <?= $form->field($model, 'current_enrollment')->textInput([
                        'type' => 'number',
                        'min' => 0,
                        'readonly' => !$model->isNewRecord,
                        'class' => 'form-control'
                    ])->hint('Current enrolled students') ?>
                </div>

                <div class="col-md-4">
                    <?= $form->field($model, 'status')->dropDownList(
                        ClassroomModel::optsStatus(),
                        ['prompt' => 'Select Status', 'class' => 'form-select']
                    ) ?>
                </div>
            </div>

            <?php if (!$model->isNewRecord): ?>
            <div class="alert alert-info mt-3" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Enrollment Status:</strong> 
                <?= $model->current_enrollment ?> / <?= $model->quota ?> students 
                (<?= $model->getEnrollmentPercentage() ?>% full)
                <?php if ($model->isFull()): ?>
                    <span class="badge bg-danger ms-2">CLASS FULL</span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Form Actions -->
        <div class="form-group mt-4">
            <div class="d-flex gap-2 justify-content-end">
                <?= Html::a('<i class="fas fa-times me-1"></i> Cancel', ['index'], [
                    'class' => 'btn btn-outline-secondary'
                ]) ?>
                <?= Html::submitButton('<i class="fas fa-save me-1"></i> Save Classroom', [
                    'class' => 'btn btn-save text-white'
                ]) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>