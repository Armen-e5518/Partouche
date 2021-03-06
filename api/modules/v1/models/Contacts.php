<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "contacts".
 *
 * @property int $id
 * @property int $type
 * @property string $message
 * @property string $status
 * @property string $date
 * @property string $user_id
 */
class Contacts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contacts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'status', 'user_id'], 'integer'],
            [['message'], 'string'],
            [['date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'message' => 'Message',
            'status' => 'Status',
            'date' => 'Date',
            'user_id' => 'user_id',
        ];
    }

    public function beforeSave($insert)
    {
        $this->user_id = Yii::$app->user->identity->getId();
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}
