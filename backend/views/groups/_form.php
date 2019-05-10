<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Groups */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="groups-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="groups-name">Users</label>
                <?= \kartik\select2\Select2::widget([
                    'name' => 'Users[]',
                    'data' => \backend\models\User::GetClients(),
                    'value' => \backend\models\UserGroups::GetAllByGroupId($model->id),
                    'maintainOrder' => true,
                    'options' => ['placeholder' => 'Users ...',
                        'id' => 'add-user',
                        'multiple' => true
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'initialize' => true,
                        'tags' => true,
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
