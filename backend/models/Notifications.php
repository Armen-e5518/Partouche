<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "notifications".
 *
 * @property int $id
 * @property string $title
 * @property string $text
 * @property string $color
 * @property string $link
 */
class Notifications extends \yii\db\ActiveRecord
{
    const COLORS = [
        'Red',
        'Green',
        'Orange'
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notifications';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text'], 'string'],
            [['title', 'color','link'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'text' => 'Text',
            'color' => 'Color',
            'link' => 'Link',
        ];
    }

    public static function GetAll()
    {
        return self::find()->select(["title", 'id'])->indexBy('id')->column();
    }
}
