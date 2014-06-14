<?php
namespace Phalcon\UserPlugin\Models\User;

use Phalcon\Mvc\Model\Validator\Uniqueness;

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
    protected $must_change_password = 0;

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
    protected $banned = 0;

    /**
     *
     * @var integer
     */
    protected $suspended = 0;

    /**
     *
     * @var integer
     */
    protected $active = 0;

    /**
     *
     * @var string
     */
    protected $created_at;

    /**
     *
     * @var string
     */
    protected $updated_at;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (int) $id;

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
        $this->must_change_password = (bool) $must_change_password;

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
        $this->profile_id = (int) $profile_id;

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
        $this->group_id = (int) $group_id;

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
        $this->banned = (bool) $banned;

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
        $this->suspended = (bool) $suspended;

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
        $this->active = (bool) $active;

        return $this;
    }

    /**
     * Method to set the value of field created_at
     *
     * @param string $created_at
     * @return $this
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Method to set the value of field updated_at
     *
     * @param string $updated_at
     * @return $this
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return (int) $this->id;
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
        return (int) $this->profile_id;
    }

    /**
     * Returns the value of field group_id
     *
     * @return integer
     */
    public function getGroupId()
    {
        return (int) $this->group_id;
    }

    /**
     * Returns the value of field banned
     *
     * @return integer
     */
    public function getBanned()
    {
        return (bool) $this->banned;
    }

    /**
     * Returns the value of field suspended
     *
     * @return integer
     */
    public function getSuspended()
    {
        return (bool) $this->suspended;
    }

    /**
     * Returns the value of field active
     *
     * @return integer
     */
    public function getActive()
    {
        return (bool) $this->active;
    }

    /**
     * Checks if the user is banned
     *
     * @return boolean
     */
    public function isBanned()
    {
        return (bool) $this->banned;
    }

    /**
     * Checks if the user is active
     *
     * @return boolean
     */
    public function isActive()
    {
        return (bool) $this->active;
    }

    /**
     * Checks if the user is suspended
     *
     * @return boolean
     */
    public function isSuspended()
    {
        return (bool) $this->suspended;
    }

    /**
     * Returns the value of field created_at
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Returns the value of field updated_at
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Checks if the password has to be changed
     *
     * @return boolean
     */
    public function shouldPasswordBeChanged()
    {
        return (bool) $this->must_change_password;
    }

    /**
     * Validations and business logic
     */
    public function validation()
    {
        $this->validate(new Uniqueness(
            array(
                'field' => 'email',
                'message' => 'The email is already registered'
            )
        ));

        return true !== $this->validationHasFailed();
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
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {
        if (empty($this->password)) {
            $tempPassword = preg_replace('/[^a-zA-Z0-9]/', '', base64_encode(openssl_random_pseudo_bytes(12)));
            $this->must_change_password = 1;
            $this->password = $this->getDI()->getSecurity()->hash($tempPassword);
        }

        if (1 !== $this->active) {
            $this->active = 0;
        }
    }

    public function beforeValidation()
    {
        $this->created_at = date("Y-m-d H:i:s"); // Don't use mysql server time, but use application's timezone
    }

    /**
     * Send a confirmation e-mail to the user if the account is not active
     */
    public function afterSave()
    {
        if (true === $this->isActive()) {
            return;
        }
        $emailConfirmation = new UserEmailConfirmations();
        $emailConfirmation->setUserId($this->id);

        if ($emailConfirmation->save()) {
            $this->getDI()->getFlashSession()->notice(
                'A confirmation mail has been sent to ' . $this->email
            );
        }
    }
}
