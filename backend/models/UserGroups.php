<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "user_groups".
 *
 * @property int $id
 * @property int $group_id
 * @property int $user_id
 */
class UserGroups extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_groups';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['group_id', 'user_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_id' => 'Group ID',
            'user_id' => 'User ID',
        ];
    }

    public static function GetAllByGroupId($id)
    {
        return self::find()->select(['user_id', 'user_id'])->where(['group_id' => $id])->indexBy('user_id')->column();
    }
}
