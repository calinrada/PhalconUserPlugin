<?php
namespace Phalcon\UserPlugin\Models\User;

use Phalcon\Mvc\Model\Validator\Uniqueness;

/**
 * Phalcon\UserPlugin\Models\User\User
 */
class User extends \Phalcon\Mvc\Model
{
    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var string
     */
    protected $email;

    /**
     *
     * @var string
     */
    protected $password;

    /**
     *
     * @var string
     */
    protected $facebook_id;

    /**
     *
     * @var string
     */
    protected $facebook_name;

    /**
     *
     * @var string
     */
    protected $facebook_data;

    /**
     *
     * @var integer
     */
    protected $linkedin_id;

    /**
     *
     * @var string
     */
    protected $linkedin_name;

    /**
     *
     * @var string
     */
    protected $linkedin_data;

    /**
     *
     * @var string
     */
    protected $gplus_id;

    /**
     *
     * @var string
     */
    protected $gplus_name;

    /**
     *
     * @var string
     */
    protected $gplus_data;

    /**
     *
     * @var string
     */
    protected $twitter_id;

    /**
     *
     * @var string
     */
    protected $twitter_name;

    /**
     *
     * @var string
     */
    protected $twitter_data;

    /**
     *
     * @var integer
     */
    protected $must_change_password;

    /**
     *
     * @var integer
     */
    protected $profile_id;

    /**
     *
     * @var integer
     */
    protected $group_id;

    /**
     *
     * @var integer
     */
    protected $banned;

    /**
     *
     * @var integer
     */
    protected $suspended;

    /**
     *
     * @var integer
     */
    protected $active;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Method to set the value of field email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Method to set the value of field password
     *
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Method to set the value of field facebook_id
     *
     * @param string $facebook_id
     * @return $this
     */
    public function setFacebookId($facebook_id)
    {
        $this->facebook_id = $facebook_id;
        return $this;
    }

    /**
     * Method to set the value of field facebook_name
     *
     * @param string $facebook_name
     * @return $this
     */
    public function setFacebookName($facebook_name)
    {
        $this->facebook_name = $facebook_name;
        return $this;
    }

    /**
     * Method to set the value of field facebook_data
     *
     * @param string $facebook_data
     * @return $this
     */
    public function setFacebookData($facebook_data)
    {
        $this->facebook_data = $facebook_data;
        return $this;
    }

    /**
     * Method to set the value of field linkedin_id
     *
     * @param integer $linkedin_id
     * @return $this
     */
    public function setLinkedinId($linkedin_id)
    {
        $this->linkedin_id = $linkedin_id;
        return $this;
    }

    /**
     * Method to set the value of field linkedin_name
     *
     * @param string $linkedin_name
     * @return $this
     */
    public function setLinkedinName($linkedin_name)
    {
        $this->linkedin_name = $linkedin_name;
        return $this;
    }

    /**
     * Method to set the value of field linkedin_data
     *
     * @param string $linkedin_data
     * @return $this
     */
    public function setLinkedinData($linkedin_data)
    {
        $this->linkedin_data = $linkedin_data;
        return $this;
    }

    /**
     * Method to set the value of field gplus_id
     *
     * @param string $gplus_id
     * @return $this
     */
    public function setGplusId($gplus_id)
    {
        $this->gplus_id = $gplus_id;
        return $this;
    }

    /**
     * Method to set the value of field gplus_name
     *
     * @param string $gplus_name
     * @return $this
     */
    public function setGplusName($gplus_name)
    {
        $this->gplus_name = $gplus_name;
        return $this;
    }

    /**
     * Method to set the value of field gplus_data
     *
     * @param string $gplus_data
     * @return $this
     */
    public function setGplusData($gplus_data)
    {
        $this->gplus_data = $gplus_data;
        return $this;
    }

    /**
     * Method to set the value of field twitter_id
     *
     * @param string $twitter_id
     * @return $this
     */
    public function setTwitterId($twitter_id)
    {
        $this->twitter_id = $twitter_id;
        return $this;
    }

    /**
     * Method to set the value of field twitter_name
     *
     * @param string $twitter_name
     * @return $this
     */
    public function setTwitterName($twitter_name)
    {
        $this->twitter_name = $twitter_name;
        return $this;
    }

    /**
     * Method to set the value of field twitter_data
     *
     * @param string $twitter_data
     * @return $this
     */
    public function setTwitterData($twitter_data)
    {
        $this->twitter_data = $twitter_data;
        return $this;
    }

    /**
     * Method to set the value of field must_change_password
     *
     * @param integer $must_change_password
     * @return $this
     */
    public function setMustChangePassword($must_change_password)
    {
        $this->must_change_password = $must_change_password;
        return $this;
    }

    /**
     * Method to set the value of field profile_id
     *
     * @param integer $profile_id
     * @return $this
     */
    public function setProfileId($profile_id)
    {
        $this->profile_id = $profile_id;
        return $this;
    }

    /**
     * Method to set the value of field group_id
     *
     * @param integer $group_id
     * @return $this
     */
    public function setGroupId($group_id)
    {
        $this->group_id = $group_id;
        return $this;
    }

    /**
     * Method to set the value of field banned
     *
     * @param integer $banned
     * @return $this
     */
    public function setBanned($banned)
    {
        $this->banned = $banned;
        return $this;
    }

    /**
     * Method to set the value of field suspended
     *
     * @param integer $suspended
     * @return $this
     */
    public function setSuspended($suspended)
    {
        $this->suspended = $suspended;
        return $this;
    }

    /**
     * Method to set the value of field active
     *
     * @param integer $active
     * @return $this
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value of field email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Returns the value of field password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the value of field facebook_id
     *
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * Returns the value of field facebook_name
     *
     * @return string
     */
    public function getFacebookName()
    {
        return $this->facebook_name;
    }

    /**
     * Returns the value of field facebook_data
     *
     * @return string
     */
    public function getFacebookData()
    {
        return $this->facebook_data;
    }

    /**
     * Returns the value of field linkedin_id
     *
     * @return integer
     */
    public function getLinkedinId()
    {
        return $this->linkedin_id;
    }

    /**
     * Returns the value of field linkedin_name
     *
     * @return string
     */
    public function getLinkedinName()
    {
        return $this->linkedin_name;
    }

    /**
     * Returns the value of field linkedin_data
     *
     * @return string
     */
    public function getLinkedinData()
    {
        return $this->linkedin_data;
    }

    /**
     * Returns the value of field gplus_id
     *
     * @return string
     */
    public function getGplusId()
    {
        return $this->gplus_id;
    }

    /**
     * Returns the value of field gplus_name
     *
     * @return string
     */
    public function getGplusName()
    {
        return $this->gplus_name;
    }

    /**
     * Returns the value of field gplus_data
     *
     * @return string
     */
    public function getGplusData()
    {
        return $this->gplus_data;
    }

    /**
     * Returns the value of field twitter_id
     *
     * @return string
     */
    public function getTwitterId()
    {
        return $this->twitter_id;
    }

    /**
     * Returns the value of field twitter_name
     *
     * @return string
     */
    public function getTwitterName()
    {
        return $this->twitter_name;
    }

    /**
     * Returns the value of field twitter_data
     *
     * @return string
     */
    public function getTwitterData()
    {
        return $this->twitter_data;
    }

    /**
     * Returns the value of field must_change_password
     *
     * @return integer
     */
    public function getMustChangePassword()
    {
        return $this->must_change_password;
    }

    /**
     * Returns the value of field profile_id
     *
     * @return integer
     */
    public function getProfileId()
    {
        return $this->profile_id;
    }

    /**
     * Returns the value of field group_id
     *
     * @return integer
     */
    public function getGroupId()
    {
        return $this->group_id;
    }

    /**
     * Returns the value of field banned
     *
     * @return integer
     */
    public function getBanned()
    {
        return $this->banned;
    }

    /**
     * Returns the value of field suspended
     *
     * @return integer
     */
    public function getSuspended()
    {
        return $this->suspended;
    }

    /**
     * Returns the value of field active
     *
     * @return integer
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Validations and business logic
     */
    public function validation()
    {
        $this->validate(new Uniqueness(
            array(
                "field"   => "email",
                "message" => "The email is already registered"
            )
        ));

        return $this->validationHasFailed() != true;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('profile_id', 'Phalcon\UserPlugin\Models\User\UserProfile', 'id', array(
            'alias' => 'profile',
            'reusable' => true
        ));

        $this->hasMany('id', 'Phalcon\UserPlugin\Models\User\UserSuccessLogins', 'user_id', array(
            'alias' => 'successLogins',
            'foreignKey' => array(
                'message' => 'User cannot be deleted because he/she has activity in the system'
            )
        ));

        $this->hasMany('id', 'Phalcon\UserPlugin\Models\User\UserPasswordChanges', 'user_id', array(
            'alias' => 'passwordChanges',
            'foreignKey' => array(
                'message' => 'User cannot be deleted because he/she has activity in the system'
            )
        ));

        $this->hasMany('id', 'Phalcon\UserPlugin\Models\User\UserResetPasswords', 'user_id', array(
            'alias' => 'resetPasswords',
            'foreignKey' => array(
                'message' => 'User cannot be deleted because he/she has activity in the system'
            )
        ));
    }

    public function getSource()
    {
        return 'user';
    }

    /**
     * @return User[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return User
     */
    public static function findFirst($parameters = array())
    {
        return parent::findFirst($parameters);
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
                'id' => 'id',
                'name' => 'name',
                'email' => 'email',
                'password' => 'password',
                'facebook_id' => 'facebook_id',
                'facebook_name' => 'facebook_name',
                'facebook_data' => 'facebook_data',
                'linkedin_id' => 'linkedin_id',
                'linkedin_name' => 'linkedin_name',
                'linkedin_data' => 'linkedin_data',
                'gplus_id' => 'gplus_id',
                'gplus_name' => 'gplus_name',
                'gplus_data' => 'gplus_data',
                'twitter_id' => 'twitter_id',
                'twitter_name' => 'twitter_name',
                'twitter_data' => 'twitter_data',
                'must_change_password' => 'must_change_password',
                'profile_id' => 'profile_id',
                'group_id' => 'group_id',
                'banned' => 'banned',
                'suspended' => 'suspended',
                'active' => 'active'
        );
    }

    /**
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {
        if (empty($this->password)) {
            $tempPassword = preg_replace('/[^a-zA-Z0-9]/', '', base64_encode(openssl_random_pseudo_bytes(12)));
            $this->mustChangePassword = 'Y';
            $this->password = $this->getDI()->getSecurity()->hash($tempPassword);
        } else {
            $this->mustChangePassword = 0;
        }

        if($this->active != 1) {
            $this->active = 0;
        }
        $this->suspended = 0;
        $this->banned = 0;
    }

    /**
     * Send a confirmation e-mail to the user if the account is not active
     */
    public function afterSave()
    {
        if ($this->active == 0) {

            $emailConfirmation = new UserEmailConfirmations();
            $emailConfirmation->setUserId($this->id);

            if ($emailConfirmation->save()) {
                $this->getDI()->getFlashSession()->notice(
                    'A confirmation mail has been sent to ' . $this->email
                );
            }
        }
    }

}
