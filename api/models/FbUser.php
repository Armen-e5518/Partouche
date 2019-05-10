<?php
namespace api\models;

use Yii;
use yii\base\Model;
use common\helpers\ImageHelper;
use common\helpers\VFileHelper;

/**
 * Facebook User Object
 */
class FbUser extends Model
{

    const FB_GENDER_MALE = 'male';

    /**
     * Facebook Id property
     */
    public $id;

    /**
     * Facebook User full name
     * Optional
     */
    public $name;

    /**
     * Facebook email
     * Optional
     * This property may be not accessible
     */
    public $email;

    /**
     * Facebook User first name property
     */
    public $first_name;

    /**
     * Facebook User last name property
     */
    public $last_name;

    /**
     * Facebook User gender property
     */
    public $gender;

    /**
     * Facebook User profile photo
     */
    private static $_photo;

    /**
     * Internal system User object
     */
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['id'], 'integer'],
            [['email'], 'email'],
            [['first_name', 'last_name'], 'string'],
            [['gender'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->_user;
    }

    /**
     * Creating new User account
     * @return bool
     */
    public function createUserAccount()
    {
        if($this->_user ===null)
        {
            $this->_user = new User(['scenario' => User::SCENARIO_CREATE_USER_FROM_FB]);
            $copied = $this->downloadFacebookPhoto();
            if(!$copied){
                self::resetPhoto();
            }

            $this->_user->setAttributes([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'gender' => $this->getSystemGender(),
                'fb_id' => $this->id,
                'avatar' => $this->getPhoto(),
            ]);
            $this->_user->generateAccessToken();
            return $this->_user->save();
        }
        return false;
    }

    /**
     * Determine gender status in system
     * @return int|null
     */
    private function getSystemGender()
    {
        $gender_status = null;
        switch($this->gender)
        {
            case self::FB_GENDER_MALE :
                $gender_status = User::GENDER_MALE;
                break;
            default:
                $gender_status = User::GENDER_FEMALE;
                break;
        }
        return $gender_status;
    }

    /**
     * @return bool
     */
    private function downloadFacebookPhoto()
    {
        if(YII_ENV_TEST) return false;

        $remoteUrl = self::getFacebookPictureUrl($this->id);
        $localFile = self::getLocalFile();
        $ImageHelper = new ImageHelper;
        $copied = $ImageHelper->download($remoteUrl, $localFile);
        return $copied;
    }

    /**
     * @param $fb_id
     * @return string
     */
    private static function getFacebookPictureUrl($fb_id)
    {
        $remoteUrl = 'https://graph.facebook.com/' . $fb_id . '/picture?type=large';
        return $remoteUrl;
    }

    /**
     * @return string
     */
    private static function getLocalFile()
    {
        self::$_photo = VFileHelper::generateJpg();
        $localFile = VFileHelper::getPathWithFile(User::UPLOAD_DIR, self::$_photo);
        return $localFile;
    }

    /**
     * @return mixed
     */
    private function getPhoto()
    {
        return self::$_photo;
    }

    private static function resetPhoto()
    {
        self::$_photo = null;
    }
}