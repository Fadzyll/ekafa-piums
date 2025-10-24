<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\ClassroomModel;

/** @var yii\web\View $this */
/** @var app\models\ClassroomModelSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<style>
.search-form-modern .form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.search-form-modern .form-group {
    margin-bottom: 0;
}

.search-form-modern label {
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
}

.search-form-modern label i {
    margin-right: 0.5rem;
    color: #667eea;
}

.search-form-modern .form-control {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.6rem 1rem;
    transition: all 0.3s ease;
}

.search-form-modern .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
}

.search-buttons {
    display: flex;
    gap: 0.8rem;
    justify-content: flex-end;
    margin-top: 1rem;
}

.btn-search-modern {
    padding: 0.6rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    border: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-search-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-search-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-search-secondary {
    background: #e9ecef;
    color: #495057;
}

.btn-search-secondary:hover {
    background: #dee2e6;
    transform: translateY(-2px);
}

.quick-filters {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 10px;
}

.quick-filter-btn {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    border: 2px solid #e5e7eb;
    background: white;
    color: #6b7280;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.quick-filter-btn:hover {
    border-color: #667eea;
    color: #667eea;
}

.quick-filter-btn.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    color: white;
}
</style>

<div class="classroom-search search-form-modern">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1,
            'id' => 'classroom-search-form'
        ],
    ]); ?>

    <!-- Quick Filters -->
    <div class="quick-filters">
        <span style="font-weight: 600; color: #495057; display: flex; align-items: center; margin-right: 0.5rem;">
            <i class="bi bi-lightning-fill" style="color: #667eea; margin-right: 0.25rem;"></i> Quick Filters:
        </span>
        <button type="button" class="quick-filter-btn" data-filter="all">All</button>
        <button type="button" class="quick-filter-btn" data-filter="open">Open Classes</button>
        <button type="button" class="quick-filter-btn" data-filter="full">Full Classes</button>
        <button type="button" class="quick-filter-btn" data-filter="progress">In Progress</button>
        <button type="button" class="quick-filter-btn" data-filter="morning">Morning</button>
        <button type="button" class="quick-filter-btn" data-filter="afternoon">Afternoon</button>
    </div>

    <div class="form-row">
        <div class="form-group">
            <?= $form->field($model, 'class_name')->textInput([
                'placeholder' => 'Search by class name...',
                'id' => 'classroomsearch-class_name'
            ])->label('<i class="bi bi-door-open"></i> Class Name') ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'grade_level')->dropDownList(
                array_merge(['' => 'All Grade Levels'], ClassroomModel::optsGradeLevel()),
                [
                    'id' => 'classroomsearch-grade_level'
                ]
            )->label('<i class="bi bi-mortarboard"></i> Grade Level') ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'session_type')->dropDownList(
                array_merge(['' => 'All Sessions'], ClassroomModel::optsSessionType()),
                [
                    'id' => 'classroomsearch-session_type'
                ]
            )->label('<i class="bi bi-clock"></i> Session Type') ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'status')->dropDownList(
                array_merge(['' => 'All Status'], ClassroomModel::optsStatus()),
                [
                    'id' => 'classroomsearch-status'
                ]
            )->label('<i class="bi bi-toggle-on"></i> Status') ?>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <?= $form->field($model, 'teacherName')->textInput([
                'placeholder' => 'Search by teacher name...',
                'id' => 'classroomsearch-teacher_name'
            ])->label('<i class="bi bi-person-fill"></i> Teacher Name') ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'year')->textInput([
                'type' => 'number',
                'placeholder' => 'e.g., 2024',
                'id' => 'classroomsearch-year'
            ])->label('<i class="bi bi-calendar"></i> Year') ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'classroom_location')->textInput([
                'placeholder' => 'Building, Room number...',
                'id' => 'classroomsearch-location'
            ])->label('<i class="bi bi-geo-alt"></i> Location') ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'class_start_date')->textInput([
                'type' => 'date',
                'id' => 'classroomsearch-start_date'
            ])->label('<i class="bi bi-calendar-check"></i> Start Date') ?>
        </div>
    </div>

    <div class="search-buttons">
        <?= Html::submitButton('<i class="bi bi-search"></i> Search', [
            'class' => 'btn-search-modern btn-search-primary',
            'id' => 'search-submit-btn'
        ]) ?>
        <?= Html::a('<i class="bi bi-arrow-clockwise"></i> Reset', ['index'], [
            'class' => 'btn-search-modern btn-search-secondary'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$script = <<< JS
// Quick filter functionality
$('.quick-filter-btn').on('click', function() {
    $('.quick-filter-btn').removeClass('active');
    $(this).addClass('active');

    var filter = $(this).data('filter');

    // Reset all fields
    $('#classroomsearch-status').val('');
    $('#classroomsearch-session_type').val('');

    // Apply quick filter
    switch(filter) {
        case 'all':
            // Do nothing - all fields are reset
            break;
        case 'open':
            $('#classroomsearch-status').val('<?= ClassroomModel::STATUS_OPEN ?>');
            break;
        case 'full':
            $('#classroomsearch-status').val('<?= ClassroomModel::STATUS_FULL ?>');
            break;
        case 'progress':
            $('#classroomsearch-status').val('<?= ClassroomModel::STATUS_IN_PROGRESS ?>');
            break;
        case 'morning':
            $('#classroomsearch-session_type').val('Morning');
            break;
        case 'afternoon':
            $('#classroomsearch-session_type').val('Afternoon');
            break;
    }

    // Auto-submit the form
    $('#classroom-search-form').submit();
});

// Set active quick filter based on current values
function setActiveQuickFilter() {
    var status = $('#classroomsearch-status').val();
    var sessionType = $('#classroomsearch-session_type').val();

    $('.quick-filter-btn').removeClass('active');

    if (!status && !sessionType) {
        $('.quick-filter-btn[data-filter="all"]').addClass('active');
    } else if (status === '<?= ClassroomModel::STATUS_OPEN ?>' && !sessionType) {
        $('.quick-filter-btn[data-filter="open"]').addClass('active');
    } else if (status === '<?= ClassroomModel::STATUS_FULL ?>' && !sessionType) {
        $('.quick-filter-btn[data-filter="full"]').addClass('active');
    } else if (status === '<?= ClassroomModel::STATUS_IN_PROGRESS ?>' && !sessionType) {
        $('.quick-filter-btn[data-filter="progress"]').addClass('active');
    } else if (sessionType === 'Morning' && !status) {
        $('.quick-filter-btn[data-filter="morning"]').addClass('active');
    } else if (sessionType === 'Afternoon' && !status) {
        $('.quick-filter-btn[data-filter="afternoon"]').addClass('active');
    }
}

// Set active filter on page load
$(document).ready(function() {
    setActiveQuickFilter();
});
JS;
$this->registerJs($script);
?>
