<?php

namespace api\modules\v1\controllers;


use api\components\VActiveController;
use api\modules\v1\models\Contacts;
use common\models\User;


/**
 * Class AttachmentsController
 * @package api\modules\v1\controllers
 */
class ContactController extends VActiveController
{

    public $modelClass = 'api\modules\v1\models\Contacts';


    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        // disable the "delete", "create", "update", "view" and "options" actions
        unset($actions['delete'], $actions['create'], $actions['update'], $actions['view'], $actions['options']);
        return $actions;
    }

    public function actionIndex()
    {
        return [
            1 => 'ok'
        ];
    }


    public function actionAddContact()
    {
        $model = new Contacts();
        if (\Yii::$app->request->post()) {
            if (($model->load(\Yii::$app->request->post(), '') && $model->save())) {
                $user = User::findOne($model->user_id);
                \Yii::$app
                    ->mailer
                    ->compose(
                        [
                            'html' => 'contact',
                            'text' => 'contact'
                        ],
                        [
                            'model' => $model,
                        ]
                    )
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->params['supportEmailTitle']])
                    ->setTo(\Yii::$app->params['contact_mail'])
                    ->setSubject(\backend\models\Contacts::CONST_TYPE[$model->type] . ' - ' . $user->last_name)
                    ->send();
            }
            return [
                'success' => true,
                'errors' => $model->getErrors(),
            ];
        }
        return [
            'success' => false,
            'errors' => $model->getErrors(),
        ];

    }

}