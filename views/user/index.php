<?php

use app\models\Users;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Users Management';
$this->params['breadcrumbs'][] = $this->title;
$this->params['hideTitle'] = true;

// Register FontAwesome if not already available
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
?>

<style>
.users-header {
    background: linear-gradient(135deg, #004135 0%, #11684d 50%, #00a86b 100%);
    border-radius: 20px 20px 0 0;
    padding: 2rem;
    color: white;
    margin-bottom: 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.users-header h3 {
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: white;
}

.users-header p {
    margin: 0.5rem 0 0 0;
    opacity: 0.9;
    font-size: 0.95rem;
    color: white;
}

.header-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.filter-toggle-btn {
    background: white;
    color: #003829;
    border: none;
    border-radius: 10px;
    padding: 0.6rem 1.2rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.filter-toggle-btn:hover {
    background: rgba(255, 255, 255, 0.9);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.filter-toggle-btn i {
    font-size: 1.1rem;
}

.btn-create-user {
    background: white;
    color: #003829;
    border: none;
    border-radius: 10px;
    padding: 0.6rem 1.2rem;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-create-user:hover {
    background: rgba(255, 255, 255, 0.9);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    color: #003829;
}

.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.stats-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    border: 2px solid #f3f4f6;
    transition: all 0.3s ease;
    text-align: center;
}

.stats-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
    border-color: #00b377;
}

.stats-card .icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    margin-bottom: 1rem;
}

.stats-card.total .icon {
    background: linear-gradient(135deg, #003829 0%, #00563d 100%);
    color: white;
}

.stats-card.total .stat-number {
    color: #00b377;
}

.stats-card.admin .icon {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    color: white;
}

.stats-card.admin .stat-number {
    color: #8b5cf6;
}

.stats-card.teacher .icon {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
}

.stats-card.teacher .stat-number {
    color: #3b82f6;
}

.stats-card.parent .icon {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.stats-card.parent .stat-number {
    color: #f59e0b;
}

.stat-number {
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 0.25rem;
}

.stat-label {
    color: #6b7280;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Search Section Styles */
.search-filter-container {
    background: white;
    border-radius: 16px;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    transition: all 0.3s ease;
}

.search-filter-container.collapsed {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.search-content {
    padding: 1.5rem;
    display: none;
}

.search-content.show {
    display: block;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.users-table-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
}

.table {
    margin-bottom: 0;
}

.table thead th {
    background: #f9fafb;
    color: #374151;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
    padding: 1rem;
    border-bottom: 2px solid #e5e7eb;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background: #f9fafb;
    transform: scale(1.002);
}

.table tbody td {
    padding: 1rem;
    vertical-align: middle;
}

.badge-role {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.75rem;
    letter-spacing: 0.025em;
}

.badge-admin {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    color: white;
}

.badge-teacher {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
}

.badge-parent {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #003829 0%, #00563d 100%);
    color: white;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-right: 0.5rem;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.action-buttons a {
    width: 35px;
    height: 35px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    transition: all 0.2s ease;
    text-decoration: none;
}

.action-buttons .btn-view {
    background: #3b82f6;
    color: white;
}

.action-buttons .btn-view:hover {
    background: #2563eb;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    color: white;
}

.action-buttons .btn-edit {
    background: #f59e0b;
    color: white;
}

.action-buttons .btn-edit:hover {
    background: #d97706;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    color: white;
}

.action-buttons .btn-delete {
    background: #ef4444;
    color: white;
}

.action-buttons .btn-delete:hover {
    background: #dc2626;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    color: white;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-state i {
    font-size: 4rem;
    color: #d1d5db;
    margin-bottom: 1rem;
}

.empty-state h4 {
    color: #6b7280;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #9ca3af;
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.users-table-card {
    animation: fadeInUp 0.5s ease;
}

@media (max-width: 768px) {
    .stats-container {
        grid-template-columns: 1fr;
    }

    .users-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }

    .header-actions {
        width: 100%;
        justify-content: space-between;
        flex-wrap: wrap;
    }
}
</style>

<div class="users-index">
    <div class="card shadow-lg" style="border: none; overflow: hidden;">
        <div class="users-header">
            <div>
                <h3>
                    <i class="fas fa-users-cog"></i>
                    Users Management
                </h3>
                <p>Manage all system users and their roles</p>
            </div>
            <div class="header-actions">
                <button class="filter-toggle-btn" id="searchToggle">
                    <i class="bi bi-funnel"></i>
                    <span>Search & Filter</span>
                </button>
                <?= Html::a('<i class="fas fa-plus"></i> Create New User', ['create'], ['class' => 'btn-create-user']) ?>
            </div>
        </div>

        <div class="card-body p-4">
            <!-- Statistics Cards -->
            <div class="stats-container">
                <div class="stats-card total">
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-label">Total Users</div>
                    <div class="stat-number"><?= $dataProvider->totalCount ?></div>
                </div>
                <div class="stats-card admin">
                    <div class="icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="stat-label">Admins</div>
                    <div class="stat-number"><?= Users::find()->where(['role' => 'Admin'])->count() ?></div>
                </div>
                <div class="stats-card teacher">
                    <div class="icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="stat-label">Teachers</div>
                    <div class="stat-number"><?= Users::find()->where(['role' => 'Teacher'])->count() ?></div>
                </div>
                <div class="stats-card parent">
                    <div class="icon">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <div class="stat-label">Parents</div>
                    <div class="stat-number"><?= Users::find()->where(['role' => 'Parent'])->count() ?></div>
                </div>
            </div>

            <!-- Search & Filter Section (Collapsible) -->
            <div class="search-filter-container collapsed" id="searchContainer">
                <div class="search-content" id="searchContent">
                    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                </div>
            </div>

            <!-- Data Grid -->
            <?php Pjax::begin(['id' => 'users-grid', 'enablePushState' => false]); ?>

            <div class="users-table-card">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => null,
                'tableOptions' => ['class' => 'table table-hover'],
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'header' => 'No.',
                        'headerOptions' => ['style' => 'width: 60px; text-align: center;'],
                        'contentOptions' => ['style' => 'text-align: center; font-weight: 600;'],
                    ],
                    [
                        'attribute' => 'username',
                        'format' => 'raw',
                        'value' => function($model) {
                            return '<div style="display: flex; align-items: center;">
                                <div class="avatar-circle">
                                    ' . strtoupper(substr($model->username, 0, 1)) . '
                                </div>
                                <span style="font-weight: 500;">' . Html::encode($model->username) . '</span>
                            </div>';
                        },
                    ],
                    [
                        'attribute' => 'email',
                        'format' => 'raw',
                        'value' => function($model) {
                            return '<i class="fas fa-envelope me-2" style="color: #00b377;"></i>' . Html::encode($model->email);
                        },
                    ],
                    [
                        'attribute' => 'role',
                        'format' => 'raw',
                        'value' => function($model) {
                            $badgeClass = [
                                'Admin' => 'badge-admin',
                                'Teacher' => 'badge-teacher',
                                'Parent' => 'badge-parent',
                            ];
                            return '<span class="badge-role ' . ($badgeClass[$model->role] ?? '') . '">' . Html::encode($model->role) . '</span>';
                        },
                    ],
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'headerOptions' => ['style' => 'width: 120px; text-align: center;'],
                        'contentOptions' => ['style' => 'text-align: center;'],
                        'value' => function($model) {
                            $statusLabels = [
                                10 => ['label' => 'Active', 'class' => 'success'],
                                9 => ['label' => 'Inactive', 'class' => 'warning'],
                                0 => ['label' => 'Deleted', 'class' => 'danger'],
                            ];
                            $status = $statusLabels[$model->status] ?? ['label' => 'Unknown', 'class' => 'secondary'];
                            return '<span class="badge bg-' . $status['class'] . '">' . $status['label'] . '</span>';
                        },
                    ],
                    [
                        'attribute' => 'date_registered',
                        'format' => 'raw',
                        'value' => function($model) {
                            return '<i class="far fa-calendar-alt me-2" style="color: #00b377;"></i>' . Yii::$app->formatter->asDatetime($model->date_registered, 'php:M d, Y');
                        },
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'header' => 'Actions',
                        'headerOptions' => ['style' => 'width: 150px; text-align: center;'],
                        'contentOptions' => ['class' => 'action-buttons'],
                        'template' => '{view} {update} {delete}',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<i class="fas fa-eye"></i>', $url, [
                                    'class' => 'btn-view',
                                    'title' => 'View',
                                    'data-pjax' => '0',
                                ]);
                            },
                            'update' => function ($url, $model) {
                                return Html::a('<i class="fas fa-edit"></i>', $url, [
                                    'class' => 'btn-edit',
                                    'title' => 'Update',
                                    'data-pjax' => '0',
                                ]);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a('<i class="fas fa-trash"></i>', $url, [
                                    'class' => 'btn-delete',
                                    'title' => 'Delete',
                                    'data-confirm' => 'Are you sure you want to delete this user?',
                                    'data-method' => 'post',
                                    'data-pjax' => '0',
                                ]);
                            },
                        ],
                        'urlCreator' => function ($action, Users $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'user_id' => $model->user_id]);
                        }
                    ],
                ],
                'emptyText' => '<div class="empty-state">
                    <i class="fas fa-users"></i>
                    <h4>No Users Found</h4>
                    <p>Start by creating your first user</p>
                </div>',
            ]); ?>
            </div>

            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

<?php
$script = <<< JS
// Toggle search section
$('#searchToggle').on('click', function() {
    var container = $('#searchContainer');
    var content = $('#searchContent');
    var icon = $(this).find('i');
    var text = $(this).find('span');

    if (content.hasClass('show')) {
        content.removeClass('show').slideUp(300);
        container.addClass('collapsed');
        icon.removeClass('bi-x-lg').addClass('bi-funnel');
        text.text('Search & Filter');
    } else {
        content.addClass('show').slideDown(300);
        container.removeClass('collapsed');
        icon.removeClass('bi-funnel').addClass('bi-x-lg');
        text.text('Hide Search');
    }
});

// Smooth scroll on pagination
$('.pagination a').on('click', function() {
    $('html, body').animate({
        scrollTop: $('.users-index').offset().top - 20
    }, 300);
});

// Handle PJAX updates
$(document).on('pjax:success', function() {
    // Re-attach pagination event after PJAX update
    $('.pagination a').on('click', function() {
        $('html, body').animate({
            scrollTop: $('.users-index').offset().top - 20
        }, 300);
    });
});
JS;
$this->registerJs($script);
?>