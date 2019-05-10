<?php

namespace api\modules\v1\controllers;


use api\components\VActiveController;
use api\modules\v1\models\Products;
use backend\models\UserNotifications;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;


/**
 * Class ProductsController
 * @package api\modules\v1\controllers
 */
class NotificationsController extends VActiveController
{

    public $modelClass = 'backend\models';

//    public function behaviors()
//    {
//        $behaviors = parent::behaviors();
//        $behaviors['authenticator'] = [
//            //'class' => HttpBasicAuth::className(),
//            'class' => CompositeAuth::className(),
//            'authMethods' => [
//                HttpBasicAuth::className(),
//                HttpBearerAuth::className(),
//                QueryParamAuth::className(),
//            ],
//        ];
//        return $behaviors;
//    }

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

    public function actionGetNotifications($id)
    {
//        return $id;
        return [
            'nots' => UserNotifications::GetAll($id)
        ];
    }

    public function actionDeleteNot()
    {
        return [
            'res' => UserNotifications::deleteAll(['id' => \Yii::$app->request->post('id')])
        ];
    }
}