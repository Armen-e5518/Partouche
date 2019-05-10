<?php

namespace api\modules\v1\controllers;


use api\components\Mail;
use api\components\VActiveController;
use api\modules\v1\models\Attachments;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;


/**
 * Class AttachmentsController
 * @package api\modules\v1\controllers
 */
class AttachmentController extends VActiveController
{

    public $modelClass = 'api\modules\v1\models\Attachments';


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

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionGetAttachments()
    {
        $data = ArrayHelper::index(Attachments::GetAttachments(), null, 'group_kay');
        $arr = [];
        if (!empty($data)) {
            foreach ($data as $d) {
                array_push($arr, $d);
            }
        }
        return [
            'list' => $arr
        ];
    }

    public function actionAddAttachment()
    {
//        $p = \Yii::$app->request->post();
        $attachments = \Yii::$app->request->post('attachments');
//        return [
//            'res' =>$attachments,
//            'post' =>$p
//        ];
//        die;
        $attachments = json_decode($attachments, true);
        $group_kay = md5(microtime(true));
        if ($attachments) {
            foreach ($attachments as $kay => $attachment) {
                $model = new Attachments();
                if (\Yii::$app->request->isPost) {
                    $model->_image = UploadedFile::getInstanceByName('_image_' . $kay);
                    $name = $model->upload($attachment['status']);
                    if (!empty($name)) {
                        $model->image = (string)$name;
                        $model->_image = null;
                    }
                }
                $model->group_kay = $group_kay;
                if (!($model->load($attachment, '') && $model->save())) {
                    return [
                        'success' => false,
                        'errors' => $model->getErrors(),
                    ];
                }
                Mail::Send(\Yii::$app->user->identity->email, $model->image);
            }
        } else {
            return [
                'success' => false,
                'errors' => 'Attachments is empty',
            ];
        }
        return [
            'success' => true,
            'errors' => null,
        ];

    }

}