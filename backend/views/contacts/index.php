<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ContactsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Contacts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contacts-index">

    <p>
        <?= Html::a('Create contact', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Reset filter', ['index'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'checkbox',
                'format' => 'html',
                'value' => function ($data) {
                    return $data->checkbox == 1 ? Html::a('<i class="fa fa-toggle-on"></i>', ['status', 'id' => $data->id])
                        : Html::a('<i class="fa fa-toggle-off"></i>', ['status', 'id' => $data->id]);
                },
                'filter' => \kartik\select2\Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'checkbox',
                    'data' => [
                        'Not selected',
                        'Selected',
                    ],
                    'options' => [
                        'placeholder' => 'Status...',
                    ]
                ]),
            ],
//            'id',
//            'type',
            [
                'attribute' => 'user_id',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::a($data->first_name . ' ' . $data->last_name, ['/user/update', 'id' => $data->user_id], ['class' => '']);
                },
                'filter' => \kartik\select2\Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'user_id',
                    'data' => \backend\models\User::GetAllUsersIndex(),
                    'options' => [
                        'placeholder' => 'Users...',
                    ]
                ]),
            ],
            [
                'attribute' => 'type',
//                'format' => 'html',
                'value' => function ($data) {
                    return \backend\models\Contacts::CONST_TYPE[$data->type];
                },
                'filter' => \kartik\select2\Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'type',
                    'data' => \backend\models\Contacts::CONST_TYPE,
                    'options' => [
                        'placeholder' => 'Type...',
                    ]
                ]),
            ],

//            'message:ntext',
//            'status',
            [
                'attribute' => 'status',
//                'format' => 'html',
                'value' => function ($data) {
                    return \backend\models\Contacts::STATUS[$data->status];
                },
                'filter' => \kartik\select2\Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'status',
                    'data' => \backend\models\Contacts::STATUS,
                    'options' => [
                        'placeholder' => 'Besoin d’être rappelé ?...',
                    ]
                ]),
            ],
//            'date',
            [
                'attribute' => 'message',
                'format' => 'raw',
                'value' => function ($data) {
                    return '<textarea readonly style="width: 100%" >' . $data->message . '</textarea>';
                },

            ],
            [
                'attribute' => 'date',
                'options' => [
                    'format' => 'YYYY-MM-DD',
                ],
                'filterType' => \kartik\grid\GridView::FILTER_DATE_RANGE,
                'filterWidgetOptions' => ([
                    'attribute' => 'only_date',
                    'presetDropdown' => true,
                    'convertFormat' => false,
                    'pluginOptions' => [
//                        'separator' => ' - ',
                        'format' => 'YYYY-MM-DD',
                        'locale' => [
                            'format' => 'YYYY-MM-DD'
                        ],
                    ],
                    'pluginEvents' => [
                        "apply.daterangepicker" => "function() { apply_filter('only_date') }",
                    ],
                ])
            ],

            ['class' => 'yii\grid\ActionColumn', 'template' => ' {delete}',]
        ],
    ]); ?>
</div>
