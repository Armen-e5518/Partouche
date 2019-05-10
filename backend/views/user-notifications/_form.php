<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\UserNotifications */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-notifications-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="groups-name">Users</label>
                <?= \kartik\select2\Select2::widget([
                    'name' => 'users[]',
                    'data' => \backend\models\User::GetClients(),
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
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="groups-name">Notifications</label>
                <?= \kartik\select2\Select2::widget([
                    'name' => 'notifications[]',
                    'data' => \backend\models\Notifications::GetAll(),
                    'value' => \backend\models\UserNotifications::GetAllByUserId($model->user_id),
                    'maintainOrder' => true,
                    'options' => ['placeholder' => 'Notifications ...',
                        'id' => 'add-notifications',
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
