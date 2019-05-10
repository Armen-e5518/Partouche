<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\UserNotifications */

$this->title = 'Create User Notifications';
$this->params['breadcrumbs'][] = ['label' => 'User Notifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-notifications-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
