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
.user-management-wrapper {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2rem;
    border-radius: 20px;
    margin-bottom: 2rem;
}

.stats-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin-bottom: 1rem;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.2);
}

.stats-card .icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    margin-bottom: 1rem;
}

.stats-card.admin .icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.stats-card.teacher .icon {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.stats-card.parent .icon {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.stats-card.total .icon {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    color: white;
}

.modern-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    overflow: hidden;
    border: none;
}

.modern-card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2rem;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modern-card-header h3 {
    margin: 0;
    font-weight: 600;
    font-size: 1.8rem;
}

.search-box {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
}

.btn-modern {
    border-radius: 10px;
    padding: 0.6rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
}

.btn-create {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-create:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    color: white;
}

.table-modern {
    border-radius: 15px;
    overflow: hidden;
}

.table-modern thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.table-modern thead th {
    border: none;
    padding: 1rem;
    font-weight: 600;
}

.table-modern tbody tr {
    transition: all 0.3s ease;
}

.table-modern tbody tr:hover {
    background: #f8f9ff;
    transform: scale(1.01);
}

.badge-role {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 500;
    font-size: 0.85rem;
}

.badge-admin {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.badge-teacher {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.badge-parent {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.action-buttons a {
    width: 35px;
    height: 35px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    margin: 0 0.2rem;
    transition: all 0.3s ease;
}

.action-buttons .btn-view {
    background: #4facfe;
    color: white;
}

.action-buttons .btn-edit {
    background: #f093fb;
    color: white;
}

.action-buttons .btn-delete {
    background: #f5576c;
    color: white;
}

.action-buttons a:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 10px rgba(0,0,0,0.2);
}

.filter-toggle {
    cursor: pointer;
    color: white;
    transition: all 0.3s ease;
}

.filter-toggle:hover {
    transform: rotate(180deg);
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.search-box {
    animation: slideDown 0.5s ease;
}

/* Hide GridView filters (since we have collapsible search) */
.table-modern .filters {
    display: none;
}
</style>

<div class="users-index">
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="stats-card total">
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <h4 class="mb-1">Total Users</h4>
                <h2 class="mb-0"><?= $dataProvider->totalCount ?></h2>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stats-card admin">
                <div class="icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h4 class="mb-1">Admins</h4>
                <h2 class="mb-0"><?= Users::find()->where(['role' => 'Admin'])->count() ?></h2>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stats-card teacher">
                <div class="icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <h4 class="mb-1">Teachers</h4>
                <h2 class="mb-0"><?= Users::find()->where(['role' => 'Teacher'])->count() ?></h2>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stats-card parent">
                <div class="icon">
                    <i class="fas fa-user-friends"></i>
                </div>
                <h4 class="mb-1">Parents</h4>
                <h2 class="mb-0"><?= Users::find()->where(['role' => 'Parent'])->count() ?></h2>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="modern-card">
        <div class="modern-card-header">
            <h3><i class="fas fa-users-cog me-2"></i>Users Management</h3>
            <div>
                <span class="filter-toggle me-3" id="filterToggle" title="Toggle Filters">
                    <i class="fas fa-filter fa-lg"></i>
                </span>
                <?= Html::a('<i class="fas fa-plus me-2"></i>Create New User', ['create'], ['class' => 'btn btn-create btn-modern']) ?>
            </div>
        </div>

        <div class="card-body p-4">
            
            <!-- Search Box (Collapsible) -->
            <div class="search-box" id="searchBox" style="display: none;">
                <h5 class="mb-3"><i class="fas fa-search me-2"></i>Search & Filter</h5>
                <?php echo $this->render('_search', ['model' => $searchModel]); ?>
            </div>

            <!-- Data Grid -->
            <?php Pjax::begin(['id' => 'users-grid', 'enablePushState' => false]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel, // Re-enable filterModel for search to work
                'tableOptions' => ['class' => 'table table-hover table-modern'],
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'header' => 'No.',
                        'headerOptions' => ['style' => 'width: 60px'],
                    ],
                    [
                        'attribute' => 'username',
                        'format' => 'raw',
                        'value' => function($model) {
                            return '<div class="d-flex align-items-center">
                                <div class="avatar-circle me-2" style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
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
                            return '<i class="fas fa-envelope me-2" style="color: #667eea;"></i>' . Html::encode($model->email);
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
                        'attribute' => 'date_registered',
                        'format' => 'raw',
                        'value' => function($model) {
                            return '<i class="far fa-calendar-alt me-2" style="color: #667eea;"></i>' . Yii::$app->formatter->asDatetime($model->date_registered, 'php:M d, Y');
                        },
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'header' => 'Actions',
                        'headerOptions' => ['style' => 'width: 150px; text-align: center;'],
                        'contentOptions' => ['style' => 'text-align: center;'],
                        'template' => '<div class="action-buttons">{view} {update} {delete}</div>',
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
            ]); ?>

            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

<?php
$this->registerJs(<<<JS
    // Toggle filter box
    $('#filterToggle').on('click', function() {
        $('#searchBox').slideToggle(300);
        $(this).find('i').toggleClass('fa-filter fa-times');
    });
    
    // Handle search form submission
    $(document).on('submit', '#user-search-form', function(e) {
        e.preventDefault();
        $.pjax.reload({container: '#users-grid', url: $(this).attr('action') + '?' + $(this).serialize()});
        return false;
    });
    
    // Smooth scroll animation for table
    $(document).on('pjax:success', function() {
        $('html, body').animate({
            scrollTop: $('.modern-card').offset().top - 20
        }, 500);
    });
JS);
?>