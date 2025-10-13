<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\UserDetails $model */

$this->title = 'My Profile';
$this->params['breadcrumbs'][] = $this->title;

// fallback image
$defaultImage = Yii::getAlias('@web/images/default_profile.png');

// Normalize profile_picture_url (ensure it's relative and prefixed correctly)
$relativePath = ltrim($model->profile_picture_url, '/'); // remove any leading slashes
$uploadedPath = Yii::getAlias('@webroot/' . $relativePath);
$imageUrl = (isset($model->profile_picture_url) && file_exists($uploadedPath))
    ? Yii::getAlias('@web/' . $relativePath)
    : $defaultImage;
?>

<div class="container mt-4">
    <div class="user-profile-update">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0"><?= Html::encode($this->title) ?></h3>
            </div>

            <div class="card-body">
                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

                <div class="row">
                    <!-- Form Section -->
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'full_name')->textInput() ?>
                                <?= $form->field($model, 'ic_number')->textInput() ?>
                                <?= $form->field($model, 'age')->textInput() ?>
                                <?= $form->field($model, 'gender')->dropDownList(['Male' => 'Male', 'Female' => 'Female'], ['prompt' => 'Select']) ?>
                                <?= $form->field($model, 'race')->textInput() ?>
                                <?= $form->field($model, 'phone_number')->textInput() ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'citizenship')->textInput() ?>
                                <?= $form->field($model, 'marital_status')->textInput() ?>
                                <?= $form->field($model, 'address')->textarea(['rows' => 3]) ?>
                                <?= $form->field($model, 'city')->textInput() ?>
                                <?= $form->field($model, 'postcode')->textInput() ?>
                                <?= $form->field($model, 'state')->textInput() ?>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Picture Section -->
                    <div class="col-md-4 d-flex flex-column align-items-center justify-content-center">
                        <div class="text-center">
                            <img id="preview" src="<?= Html::encode($imageUrl) ?>" class="img-thumbnail rounded-circle mb-2"
                                 style="width: 180px; height: 180px; object-fit: cover;">
                            <p class="text-muted small fst-italic">Current Profile Picture</p>

                            <label class="form-label d-block mt-2">Upload New Profile Picture</label>
                            <?= $form->field($model, 'imageFile', [
                                'template' => '{input}{error}',
                            ])->fileInput([
                                'accept' => 'image/*',
                                'onchange' => 'loadPreview(event)',
                                'class' => 'form-control text-center'
                            ])->label(false) ?>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-4 text-end">
                    <?= Html::a('Back', ['view'], ['class' => 'btn btn-primary']) ?>
                    <?= Html::submitButton('Save Profile', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<script>
function loadPreview(event) {
    const output = document.getElementById('preview');
    const file = event.target.files[0];

    if (file) {
        output.src = URL.createObjectURL(file);
        output.onload = function () {
            URL.revokeObjectURL(output.src);
        };
    }
}
</script>
