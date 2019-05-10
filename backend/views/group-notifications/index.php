<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\UserNotificationsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User Notifications';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-notifications-index">
    
    <p>
        <?= Html::a('Create User Notifications', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
//            'first_name',
//            'last_name',
//            'notification_id',
            [
                'attribute' => 'Users',
                'format' => 'html',
                'value' => function ($data) {
                    return \backend\models\User::GetUserById($data->user_id);
                },

            ],
            [
                'attribute' => 'Notification',
                'format' => 'html',
                'value' => function ($data) {
                    $not = \backend\models\UserNotifications::GetAll($data->user_id);
                    $s = '';
                    foreach ($not as $n) {
                        $s .= '<li>' . $n['title'] . '</li>';
                    }
                    return '<ul>' . $s . '</ul>';
                },

            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Actions',
                'template' => '{update}{delete}',
                'buttons' => [

                ],
            ],
        ],
    ]); ?>
</div>
