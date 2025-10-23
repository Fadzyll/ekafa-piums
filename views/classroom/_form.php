<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\ClassroomModel;
use yii\helpers\ArrayHelper;

/** @var array $teachers */
/** @var yii\web\View $this */
/** @var app\models\ClassroomModel $model */
/** @var yii\widgets\ActiveForm $form */

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
    .nav-tabs .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        color: #6c757d;
        font-weight: 600;
        padding: 1rem 1.5rem;
        transition: all 0.3s ease;
    }
    .nav-tabs .nav-link:hover {
        color: #667eea;
        border-bottom-color: #667eea;
    }
    .nav-tabs .nav-link.active {
        color: #667eea;
        border-bottom-color: #667eea;
        background: transparent;
    }
    .tab-content {
        padding: 2rem 0;
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
    .input-group-text {
        background: #e9ecef;
        border-color: #ced4da;
    }
    .character-counter {
        font-size: 0.75rem;
        color: #6c757d;
        float: right;
    }
");

// Register JS
$this->registerJs("
    // Character counter
    $('textarea').each(function() {
        var maxLength = $(this).attr('maxlength');
        if (maxLength) {
            var counter = $('<span class=\"character-counter\"></span>');
            $(this).after(counter);
            
            var updateCounter = function() {
                var length = $(this).val().length;
                counter.text(length + '/' + maxLength);
            };
            
            $(this).on('input', updateCounter);
            updateCounter.call(this);
        }
    });

    // Age validation
    $('#classroommodel-min_age, #classroommodel-max_age').on('change', function() {
        var minAge = parseInt($('#classroommodel-min_age').val());
        var maxAge = parseInt($('#classroommodel-max_age').val());
        
        if (minAge && maxAge && minAge > maxAge) {
            alert('Minimum age cannot be greater than maximum age');
            $('#classroommodel-max_age').val('');
        }
    });

    // Quota validation
    $('#classroommodel-quota, #classroommodel-minimum_enrollment').on('change', function() {
        var quota = parseInt($('#classroommodel-quota').val());
        var minEnroll = parseInt($('#classroommodel-minimum_enrollment').val());
        
        if (quota && minEnroll && minEnroll > quota) {
            alert('Minimum enrollment cannot be greater than quota');
            $('#classroommodel-minimum_enrollment').val('');
        }
    });

    // Date validation
    $('#classroommodel-enrollment_start_date, #classroommodel-enrollment_end_date').on('change', function() {
        var startDate = $('#classroommodel-enrollment_start_date').val();
        var endDate = $('#classroommodel-enrollment_end_date').val();
        
        if (startDate && endDate && startDate > endDate) {
            alert('Enrollment start date cannot be after end date');
            $('#classroommodel-enrollment_end_date').val('');
        }
    });

    $('#classroommodel-class_start_date, #classroommodel-class_end_date').on('change', function() {
        var startDate = $('#classroommodel-class_start_date').val();
        var endDate = $('#classroommodel-class_end_date').val();
        
        if (startDate && endDate && startDate > endDate) {
            alert('Class start date cannot be after end date');
            $('#classroommodel-class_end_date').val('');
        }
    });

    // Enable tooltips
    $('[data-toggle=\"tooltip\"]').tooltip();
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

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs" id="classroomTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" 
                        data-bs-target="#basic" type="button" role="tab">
                    <i class="fas fa-info-circle me-2"></i>Basic Information
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="schedule-tab" data-bs-toggle="tab" 
                        data-bs-target="#schedule" type="button" role="tab">
                    <i class="fas fa-calendar me-2"></i>Schedule & Location
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="enrollment-tab" data-bs-toggle="tab" 
                        data-bs-target="#enrollment" type="button" role="tab">
                    <i class="fas fa-users me-2"></i>Enrollment & Fees
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="additional-tab" data-bs-toggle="tab" 
                        data-bs-target="#additional" type="button" role="tab">
                    <i class="fas fa-cog me-2"></i>Additional Details
                </button>
            </li>
        </ul>

        <div class="tab-content" id="classroomTabContent">
            <!-- Basic Information Tab -->
            <div class="tab-pane fade show active" id="basic" role="tabpanel">
                <div class="form-section">
                    <h5 class="section-title">
                        <i class="fas fa-file-alt me-2"></i>Class Details
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'class_name')->textInput([
                                'maxlength' => true,
                                'placeholder' => 'e.g., Mathematics Advanced',
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

                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'description')->textarea([
                                'rows' => 4,
                                'maxlength' => 1000,
                                'placeholder' => 'Provide a detailed description of the classroom...',
                                'class' => 'form-control'
                            ])->hint('Maximum 1000 characters') ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'objectives')->textarea([
                                'rows' => 3,
                                'maxlength' => 500,
                                'placeholder' => 'List the learning objectives...',
                                'class' => 'form-control'
                            ]) ?>
                        </div>

                        <div class="col-md-6">
                            <?= $form->field($model, 'prerequisites')->textarea([
                                'rows' => 3,
                                'maxlength' => 500,
                                'placeholder' => 'List any prerequisites...',
                                'class' => 'form-control'
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h5 class="section-title">
                        <i class="fas fa-child me-2"></i>Age Requirements
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'min_age')->textInput([
                                'type' => 'number',
                                'min' => 0,
                                'max' => 100,
                                'class' => 'form-control'
                            ])->hint('Minimum age requirement') ?>
                        </div>

                        <div class="col-md-6">
                            <?= $form->field($model, 'max_age')->textInput([
                                'type' => 'number',
                                'min' => 0,
                                'max' => 100,
                                'class' => 'form-control'
                            ])->hint('Maximum age requirement') ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schedule & Location Tab -->
            <div class="tab-pane fade" id="schedule" role="tabpanel">
                <div class="form-section">
                    <h5 class="section-title">
                        <i class="fas fa-clock me-2"></i>Session Details
                    </h5>
                    <div class="row">
                        <div class="col-md-4">
                            <?= $form->field($model, 'session_type')->dropDownList(
                                ClassroomModel::optsSessionType(),
                                ['prompt' => 'Select Session', 'class' => 'form-select']
                            ) ?>
                        </div>

                        <div class="col-md-4">
                            <?= $form->field($model, 'start_time')->input('time', [
                                'class' => 'form-control'
                            ]) ?>
                        </div>

                        <div class="col-md-4">
                            <?= $form->field($model, 'end_time')->input('time', [
                                'class' => 'form-control'
                            ]) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'days_of_week')->textInput([
                                'maxlength' => true,
                                'placeholder' => 'e.g., Monday, Wednesday, Friday',
                                'class' => 'form-control'
                            ])->hint('Comma-separated days') ?>
                        </div>

                        <div class="col-md-6">
                            <?= $form->field($model, 'session_id')->textInput([
                                'type' => 'number',
                                'class' => 'form-control'
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h5 class="section-title">
                        <i class="fas fa-map-marker-alt me-2"></i>Location Details
                    </h5>
                    <div class="row">
                        <div class="col-md-4">
                            <?= $form->field($model, 'building')->textInput([
                                'maxlength' => true,
                                'placeholder' => 'e.g., Main Building',
                                'class' => 'form-control'
                            ]) ?>
                        </div>

                        <div class="col-md-4">
                            <?= $form->field($model, 'floor')->textInput([
                                'type' => 'number',
                                'min' => 0,
                                'class' => 'form-control'
                            ]) ?>
                        </div>

                        <div class="col-md-4">
                            <?= $form->field($model, 'classroom_location')->textInput([
                                'maxlength' => true,
                                'placeholder' => 'e.g., Room 101',
                                'class' => 'form-control'
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h5 class="section-title">
                        <i class="fas fa-calendar-alt me-2"></i>Class Duration
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'class_start_date')->input('date', [
                                'class' => 'form-control'
                            ]) ?>
                        </div>

                        <div class="col-md-6">
                            <?= $form->field($model, 'class_end_date')->input('date', [
                                'class' => 'form-control'
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enrollment & Fees Tab -->
            <div class="tab-pane fade" id="enrollment" role="tabpanel">
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
                            <?= $form->field($model, 'minimum_enrollment')->textInput([
                                'type' => 'number',
                                'min' => 0,
                                'class' => 'form-control'
                            ])->hint('Minimum students required') ?>
                        </div>

                        <div class="col-md-4">
                            <?= $form->field($model, 'current_enrollment')->textInput([
                                'type' => 'number',
                                'min' => 0,
                                'readonly' => !$model->isNewRecord,
                                'class' => 'form-control'
                            ])->hint('Current enrolled students') ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'enrollment_start_date')->input('date', [
                                'class' => 'form-control'
                            ]) ?>
                        </div>

                        <div class="col-md-6">
                            <?= $form->field($model, 'enrollment_end_date')->input('date', [
                                'class' => 'form-control'
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h5 class="section-title">
                        <i class="fas fa-money-bill-wave me-2"></i>Fee Structure
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">RM</span>
                                <?= $form->field($model, 'monthly_fee', [
                                    'template' => '{input}{error}{hint}'
                                ])->textInput([
                                    'type' => 'number',
                                    'step' => '0.01',
                                    'min' => 0,
                                    'class' => 'form-control',
                                    'placeholder' => '0.00'
                                ]) ?>
                            </div>
                            <label class="form-label">Monthly Fee (RM)</label>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">RM</span>
                                <?= $form->field($model, 'registration_fee', [
                                    'template' => '{input}{error}{hint}'
                                ])->textInput([
                                    'type' => 'number',
                                    'step' => '0.01',
                                    'min' => 0,
                                    'class' => 'form-control',
                                    'placeholder' => '0.00'
                                ]) ?>
                            </div>
                            <label class="form-label">Registration Fee (RM)</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Details Tab -->
            <div class="tab-pane fade" id="additional" role="tabpanel">
                <div class="form-section">
                    <h5 class="section-title">
                        <i class="fas fa-chalkboard-teacher me-2"></i>Staff Assignment
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'user_id')->dropDownList(
                                $teachers,
                                ['prompt' => 'Select Teacher', 'class' => 'form-select']
                            )->label('Primary Teacher') ?>
                        </div>

                        <div class="col-md-6">
                            <?= $form->field($model, 'assistant_teacher_id')->dropDownList(
                                $teachers,
                                ['prompt' => 'Select Assistant Teacher', 'class' => 'form-select']
                            )->label('Assistant Teacher (Optional)') ?>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h5 class="section-title">
                        <i class="fas fa-cogs me-2"></i>Status & Visibility
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'status')->dropDownList(
                                ClassroomModel::optsStatus(),
                                ['prompt' => 'Select Status', 'class' => 'form-select']
                            ) ?>
                        </div>

                        <div class="col-md-6">
                            <?= $form->field($model, 'is_visible')->checkbox([
                                'label' => 'Make this classroom visible to students',
                                'uncheck' => 0,
                            ]) ?>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h5 class="section-title">
                        <i class="fas fa-sticky-note me-2"></i>Administrative Notes
                    </h5>
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'admin_notes')->textarea([
                                'rows' => 5,
                                'maxlength' => 1000,
                                'placeholder' => 'Add any administrative notes or comments...',
                                'class' => 'form-control'
                            ])->hint('Internal notes (not visible to students)') ?>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h5 class="section-title">
                        <i class="fas fa-image me-2"></i>Media
                    </h5>
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'class_photo_url')->textInput([
                                'maxlength' => true,
                                'placeholder' => 'https://example.com/image.jpg',
                                'class' => 'form-control'
                            ])->hint('URL to classroom photo') ?>
                        </div>
                    </div>
                </div>
            </div>
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