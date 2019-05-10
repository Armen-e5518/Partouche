<?php

namespace backend\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "attachments".
 *
 * @property int $id
 * @property int $user_id
 * @property string $comment
 * @property string $image
 * @property string $_image
 * @property int $status
 * @property int $realm_id
 * @property int $date
 * @property int $category_id
 * @property int $checkbox
 */
class Attachments extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $_image;

    public $realm_id;


    public $first_name;
    public $last_name;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'attachments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['_image'], 'file', 'extensions' => 'png, jpg'],
            [['user_id', 'status','realm_id','category_id','checkbox'], 'integer'],
            [['comment', 'image','date'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User',
            'comment' => 'Comment',
            'image' => 'Image',
            'date' => 'Date',
            'status' => 'Paiements',
            'realm_id' => 'Realm id',
            'category_id' => 'Category',
            'checkbox' => 'Status',
        ];
    }

    public function upload()
    {
        if (!empty($this->_image)) {
            $img_name = md5(microtime(true)) . '.' . $this->_image->extension;
            if ($this->_image->saveAs(Yii::getAlias('@frontend') . '/web/attachments/' . $img_name)) {
                return $img_name;
            }
        }
        return false;
    }


}
