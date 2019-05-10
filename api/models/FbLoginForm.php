<?php
namespace api\models;

use Yii;
use yii\base\Model;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

/**
 * Facebook Login form
 */
class FbLoginForm extends Model
{

    /**
     * Facebook Id property
     */
    public $fb_id;

    /**
     * Facebook fb_token property
     */
    public $fb_token;

    /**
     * Facebook User email property optional
     */
    public $fb_email;

    /**
     * Facebook User object
     */
    private $_fb_user;

    /**
     * System User object
     */
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['fb_id', 'fb_token'], 'required'],
            [['fb_id'], 'integer'],
            [['fb_email'], 'email'],
            [['fb_token'], 'string'],
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
     * 2 cases in this action to get User data
     * case 1 when found User by fb_id then return access_token and return
     * case 2 when found User by email then updating User object to set fb_id and return access_token
     * In both cases function will return true
     * Otherwise next step will call to facebook to retrieve user Data for create new User account.
     * @see callToFacebook()
     * @uses UserController::actionAuthWithFacebook()
     * @return bool
     */
    public function hasUserAccountAccess()
    {
        $hasAccess = false;
        $user = $this->getUserByFbId();
        if (!$user) {
            $user = $this->getUserByFbEmail();
            if($user){
                // update fb_id and return access token
                $hasAccess = true;
                $user->updateFacebookId($this->fb_id);
            }
        }else{
            // case found User by fb_id
            $hasAccess = true;
        }
        return $hasAccess;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->_user;
    }

    /**
     * Find User by fb_id
     * @return User|array|null
     */
    private function getUserByFbId()
    {
        if ($this->_user === null) {
            $this->_user = User::find()->where(['fb_id' => $this->fb_id])->active()->one();
        }
        return $this->_user;
    }

    /**
     * Find User by email
     * @return User|array|null
     */
    private function getUserByFbEmail()
    {
        $query = User::find();
        if(isset($this->fb_email)){
            $query->orWhere([ 'email' => $this->fb_email]);
            $query->active();
            if ($this->_user === null) {
                $this->_user = $query->one();
            }
        }
        return $this->_user;
    }

    /**
     * Calling Facebook by fb access token
     * Trying get fields for new User account
     * @return bool
     */
    public function callToFacebook()
    {
        if(YII_ENV_TEST){
            $this->_fb_user = Yii::$app->params['fb_test_user'];
            return true;
        }
        $success = true;
        $FbConfig = \Yii::$app->params['Facebook'];
        $fb = new Facebook($FbConfig);
        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->get('/me?fields=id,name,email,gender,birthday,first_name,last_name', $this->fb_token);
            if($this->_fb_user === null){
                $this->_fb_user = $response->getGraphUser();
            }

        } catch(FacebookResponseException $e) {
            $this->addError('fb_token', $e->getMessage());
            $success = false;
			Yii::info('FacebookResponseException in callToFacebook: ' . $e->getMessage(), 'facebook-user');
        } catch(FacebookSDKException $e) {
            $this->addError('fb_token', $e->getMessage());
            $success = false;
			Yii::info('FacebookSDKException in callToFacebook: ' . $e->getMessage(), 'facebook-user');
        }

        return $success;
    }

    /**
     * Get Facebook User Data
     * @return mixed
     */
    public function getFbUser()
    {
        return $this->_fb_user;
    }
}