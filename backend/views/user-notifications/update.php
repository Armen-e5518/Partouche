<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\UserNotifications */

$this->title = 'Update User Notifications: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'User Notifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-notifications-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
