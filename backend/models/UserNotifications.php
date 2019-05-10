<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "user_notifications".
 *
 * @property int $id
 * @property int $user_id
 * @property int $notification_id
 */
class UserNotifications extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_notifications';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'notification_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ',
            'notification_id' => 'Notifications',
        ];
    }

    public static function GetAllByUserId($id)
    {
        return self::find()->select(["notification_id", 'notification_id'])->indexBy('notification_id')->where(['user_id' => $id])->column();
    }

    public static function GetAll($id)
    {
        return (new \yii\db\Query())
            ->select(
                [
                    'un.id as not_id',
                    'n.*',
                ])
            ->from(self::tableName() . ' as un')
            ->leftJoin(Notifications::tableName() . ' n', 'un.notification_id = n.id')
            ->where(['un.user_id' => $id])
            ->all();
    }
}
