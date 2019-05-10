<?php

namespace backend\models;

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
 * @property string $checkbox
 */
class Contacts extends \yii\db\ActiveRecord
{
    public $first_name;
    public $last_name;

    const  CONST_TYPE = [
        0 => 'Choisissez votre catégorie',
        1 => 'Technique',
        2 => 'Administrative',
        3 => 'Fiscale',
        4 => 'Sociale',
        5 => 'Autres',
    ];
    const  STATUS = [
        0 => 'NO',
        1 => 'YES',
    ];

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
            [['type', 'status','user_id','checkbox'], 'integer'],
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
            'status' => 'Besoin d’être rappelé ?',
            'date' => 'Date',
            'user_id' => 'User',
            'checkbox' => 'Show status',
        ];
    }

}
