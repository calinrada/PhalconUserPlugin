<?php

namespace Phalcon\UserPlugin\Models\User;

use Phalcon\Mvc\Model\Validator\Uniqueness;

class User extends \Phalcon\Mvc\Model
{
    const STATUS_INACTIVE = 0;

    const STATUS_ACTIVE = 1;

    const STATUS_SUSPENDED = 2;

    const STATUS_BANNED = 3;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $first_name;

    /**
     * @var string
     */
    protected $last_name;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $facebook_id;

    /**
     * @var string
     */
    protected $facebook_name;

    /**
     * @var string
     */
    protected $facebook_data;

    /**
     * @var int
     */
    protected $linkedin_id;

    /**
     * @var string
     */
    protected $linkedin_name;

    /**
     * @var string
     */
    protected $linkedin_data;

    /**
     * @var string
     */
    protected $gplus_id;

    /**
     * @var string
     */
    protected $gplus_name;

    /**
     * @var string
     */
    protected $gplus_data;

    /**
     * @var string
     */
    protected $twitter_id;

    /**
     * @var string
     */
    protected $twitter_name;

    /**
     * @var string
     */
    protected $twitter_data;

    /**
     * @var int
     */
    protected $must_change_password = 0;

    /**
     * @var int
     */
    protected $profile_id;

    /**
     * @var int
     */
    protected $group_id;

    /**
     * @deprecated Left behind for backward compatibility. Use $status column instead
     *
     * @var int
     */
    protected $banned = 0;

    /**
     * @deprecated Left behind for backward compatibility. Use $status column instead
     *
     * @var int
     */
    protected $suspended = 0;

    /**
     * @deprecated Left behind for backward compatibility. Use $status column instead
     *
     * @var int
     */
    protected $active = 0;

    /**
     * @var int
     */
    protected $status = 0;

    /**
     * @var string
     */
    protected $created_at;

    /**
     * @var string
     */
    protected $updated_at;

    /**
     * Method to set the value of field id.
     *
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (int) $id;

        return $this;
    }

    /**
     * Method to set the value of field name.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Method to set the value of field first_name.
     *
     * @param string $first_name
     *
     * @return $this
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;

        return $this;
    }

    /**
     * Method to set the value of field last_name.
     *
     * @param string $last_name
     *
     * @return $this
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;

        return $this;
    }

    /**
     * Method to set the value of field email.
     *
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Method to set the value of field password.
     *
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Method to set the value of field facebook_id.
     *
     * @param string $facebook_id
     *
     * @return $this
     */
    public function setFacebookId($facebook_id)
    {
        $this->facebook_id = $facebook_id;

        return $this;
    }

    /**
     * Method to set the value of field facebook_name.
     *
     * @param string $facebook_name
     *
     * @return $this
     */
    public function setFacebookName($facebook_name)
    {
        $this->facebook_name = $facebook_name;

        return $this;
    }

    /**
     * Method to set the value of field facebook_data.
     *
     * @param string $facebook_data
     *
     * @return $this
     */
    public function setFacebookData($facebook_data)
    {
        $this->facebook_data = $facebook_data;

        return $this;
    }

    /**
     * Method to set the value of field linkedin_id.
     *
     * @param int $linkedin_id
     *
     * @return $this
     */
    public function setLinkedinId($linkedin_id)
    {
        $this->linkedin_id = $linkedin_id;

        return $this;
    }

    /**
     * Method to set the value of field linkedin_name.
     *
     * @param string $linkedin_name
     *
     * @return $this
     */
    public function setLinkedinName($linkedin_name)
    {
        $this->linkedin_name = $linkedin_name;

        return $this;
    }

    /**
     * Method to set the value of field linkedin_data.
     *
     * @param string $linkedin_data
     *
     * @return $this
     */
    public function setLinkedinData($linkedin_data)
    {
        $this->linkedin_data = $linkedin_data;

        return $this;
    }

    /**
     * Method to set the value of field gplus_id.
     *
     * @param string $gplus_id
     *
     * @return $this
     */
    public function setGplusId($gplus_id)
    {
        $this->gplus_id = $gplus_id;

        return $this;
    }

    /**
     * Method to set the value of field gplus_name.
     *
     * @param string $gplus_name
     *
     * @return $this
     */
    public function setGplusName($gplus_name)
    {
        $this->gplus_name = $gplus_name;

        return $this;
    }

    /**
     * Method to set the value of field gplus_data.
     *
     * @param string $gplus_data
     *
     * @return $this
     */
    public function setGplusData($gplus_data)
    {
        $this->gplus_data = $gplus_data;

        return $this;
    }

    /**
     * Method to set the value of field twitter_id.
     *
     * @param string $twitter_id
     *
     * @return $this
     */
    public function setTwitterId($twitter_id)
    {
        $this->twitter_id = $twitter_id;

        return $this;
    }

    /**
     * Method to set the value of field twitter_name.
     *
     * @param string $twitter_name
     *
     * @return $this
     */
    public function setTwitterName($twitter_name)
    {
        $this->twitter_name = $twitter_name;

        return $this;
    }

    /**
     * Method to set the value of field twitter_data.
     *
     * @param string $twitter_data
     *
     * @return $this
     */
    public function setTwitterData($twitter_data)
    {
        $this->twitter_data = $twitter_data;

        return $this;
    }

    /**
     * Method to set the value of field must_change_password.
     *
     * @param int $must_change_password
     *
     * @return $this
     */
    public function setMustChangePassword($must_change_password)
    {
        $this->must_change_password = (bool) $must_change_password;

        return $this;
    }

    /**
     * Method to set the value of field profile_id.
     *
     * @param int $profile_id
     *
     * @return $this
     */
    public function setProfileId($profile_id)
    {
        $this->profile_id = (int) $profile_id;

        return $this;
    }

    /**
     * Method to set the value of field group_id.
     *
     * @param int $group_id
     *
     * @return $this
     */
    public function setGroupId($group_id)
    {
        $this->group_id = (int) $group_id;

        return $this;
    }

    /**
     * Method to set the value of field banned.
     *
     * @deprecated Left behind for backward compatibility. Use $status column instead
     *
     * @param int $banned
     *
     * @return $this
     */
    public function setBanned($banned)
    {
        $this->banned = (bool) $banned;

        return $this;
    }

    /**
     * Method to set the value of field suspended.
     *
     * @deprecated Left behind for backward compatibility. Use $status column instead
     *
     * @param int $suspended
     *
     * @return $this
     */
    public function setSuspended($suspended)
    {
        $this->suspended = (bool) $suspended;

        return $this;
    }

    /**
     * Method to set the value of field active.
     *
     * @deprecated Left behind for backward compatibility. Use $status column instead
     *
     * @param int $active
     *
     * @return $this
     */
    public function setActive($active)
    {
        $this->active = (bool) $active;

        return $this;
    }

    /**
     * Method to set the value of field status.
     *
     * @param int $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Method to set the value of field created_at.
     *
     * @param string $created_at
     *
     * @return $this
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Method to set the value of field updated_at.
     *
     * @param string $updated_at
     *
     * @return $this
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Returns the value of field id.
     *
     * @return int
     */
    public function getId()
    {
        return (int) $this->id;
    }

    /**
     * Returns the value of field name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value of field first_name.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Returns the value of field last_name.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Returns the value of field email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Returns the value of field password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the value of field facebook_id.
     *
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * Returns the value of field facebook_name.
     *
     * @return string
     */
    public function getFacebookName()
    {
        return $this->facebook_name;
    }

    /**
     * Returns the value of field facebook_data.
     *
     * @return string
     */
    public function getFacebookData()
    {
        return $this->facebook_data;
    }

    /**
     * Returns the value of field linkedin_id.
     *
     * @return int
     */
    public function getLinkedinId()
    {
        return $this->linkedin_id;
    }

    /**
     * Returns the value of field linkedin_name.
     *
     * @return string
     */
    public function getLinkedinName()
    {
        return $this->linkedin_name;
    }

    /**
     * Returns the value of field linkedin_data.
     *
     * @return string
     */
    public function getLinkedinData()
    {
        return $this->linkedin_data;
    }

    /**
     * Returns the value of field gplus_id.
     *
     * @return string
     */
    public function getGplusId()
    {
        return $this->gplus_id;
    }

    /**
     * Returns the value of field gplus_name.
     *
     * @return string
     */
    public function getGplusName()
    {
        return $this->gplus_name;
    }

    /**
     * Returns the value of field gplus_data.
     *
     * @return string
     */
    public function getGplusData()
    {
        return $this->gplus_data;
    }

    /**
     * Returns the value of field twitter_id.
     *
     * @return string
     */
    public function getTwitterId()
    {
        return $this->twitter_id;
    }

    /**
     * Returns the value of field twitter_name.
     *
     * @return string
     */
    public function getTwitterName()
    {
        return $this->twitter_name;
    }

    /**
     * Returns the value of field twitter_data.
     *
     * @return string
     */
    public function getTwitterData()
    {
        return $this->twitter_data;
    }

    /**
     * Returns the value of field must_change_password.
     *
     * @return int
     */
    public function getMustChangePassword()
    {
        return $this->must_change_password;
    }

    /**
     * Returns the value of field profile_id.
     *
     * @return int
     */
    public function getProfileId()
    {
        return (int) $this->profile_id;
    }

    /**
     * Returns the value of field group_id.
     *
     * @return int
     */
    public function getGroupId()
    {
        return (int) $this->group_id;
    }

    /**
     * Returns the value of field banned.
     *
     * @deprecated Left behind for backward compatibility. Use $status column instead
     *
     * @return int
     */
    public function getBanned()
    {
        return (bool) $this->banned;
    }

    /**
     * Returns the value of field suspended.
     *
     * @deprecated Left behind for backward compatibility. Use $status column instead
     *
     * @return int
     */
    public function getSuspended()
    {
        return (bool) $this->suspended;
    }

    /**
     * Returns the value of field active.
     *
     * @deprecated Left behind for backward compatibility. Use $status column instead
     *
     * @return int
     */
    public function getActive()
    {
        return (bool) $this->active;
    }

    /**
     * Checks if the user is banned.
     *
     * @deprecated Left behind for backward compatibility. Use $status column instead
     *
     * @return bool
     */
    public function isBanned()
    {
        return (bool) $this->banned;
    }

    /**
     * Checks if the user is active.
     *
     * @deprecated Left behind for backward compatibility. Use $status column instead
     *
     * @return bool
     */
    public function isActive()
    {
        return (bool) $this->active;
    }

    /**
     * Checks if the user is suspended.
     *
     * @deprecated Left behind for backward compatibility. Use $status column instead
     *
     * @return bool
     */
    public function isSuspended()
    {
        return (bool) $this->suspended;
    }

    /**
     * Get current status.
     *
     * @return int
     */
    public function getStatus()
    {
        return (int) $this->status;
    }

    /**
     * Returns the value of field created_at.
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Returns the value of field updated_at.
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Checks if the password has to be changed.
     *
     * @return bool
     */
    public function shouldPasswordBeChanged()
    {
        return (bool) $this->must_change_password;
    }

    /**
     * Validations and business logic.
     */
    public function validation()
    {
        $this->validate(new Uniqueness(
            array(
                'field' => 'email',
                'message' => 'The email is already registered',
            )
        ));

        return true !== $this->validationHasFailed();
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasOne('id', 'Phalcon\UserPlugin\Models\User\UserProfile', 'user_id', array(
            'alias' => 'profile',
            'reusable' => true,
            'foreignKey' => array(
                'action' => \Phalcon\Mvc\Model\Relation::ACTION_CASCADE,
            ),
        ));

        $this->hasMany('id', 'Phalcon\UserPlugin\Models\User\UserSuccessLogins', 'user_id', array(
            'alias' => 'successLogins',
            'foreignKey' => array(
                'action' => \Phalcon\Mvc\Model\Relation::ACTION_CASCADE,
            ),
        ));

        $this->belongsTo('group_id', 'Phalcon\UserPlugin\Models\User\UserGroups', 'id', array(
            'alias' => 'group',
            'reusable' => true,
        ));

        $this->hasMany('id', 'Phalcon\UserPlugin\Models\User\UserPasswordChanges', 'user_id', array(
            'alias' => 'passwordChanges',
            'foreignKey' => array(
                'action' => \Phalcon\Mvc\Model\Relation::ACTION_CASCADE,
            ),
        ));

        $this->hasMany('id', 'Phalcon\UserPlugin\Models\User\UserResetPasswords', 'user_id', array(
            'alias' => 'resetPasswords',
            'foreignKey' => array(
                'action' => \Phalcon\Mvc\Model\Relation::ACTION_CASCADE,
            ),
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
     * Before create the user assign a password.
     */
    public function beforeValidationOnCreate()
    {
        if (empty($this->password)) {
            $tempPassword = preg_replace('/[^a-zA-Z0-9]/', '', base64_encode(openssl_random_pseudo_bytes(12)));
            $this->must_change_password = 1;
            $this->password = $this->getDI()->getSecurity()->hash($tempPassword);
        }

        if (empty($this->status)) {
            $this->status == static::STATUS_INACTIVE;
        }

        $this->created_at = date('Y-m-d H:i:s');
    }

    public function beforeValidationOnUpdate()
    {
        $this->updated_at = date('Y-m-d H:i:s');
    }

    /**
     * Send a confirmation e-mail to the user if the account is not active.
     */
    public function afterCreate()
    {
        if ($this->getStatus() === static::STATUS_ACTIVE) {
            return;
        }

        $emailConfirmation = new UserEmailConfirmations();
        $emailConfirmation->setUserId($this->id);

        if ($emailConfirmation->save()) {
            $this->getDI()->getFlashSession()->notice(
                'A confirmation mail has been sent to '.$this->email
            );
        }
    }
}
