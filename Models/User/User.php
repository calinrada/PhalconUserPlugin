<?php
namespace Phalcon\UserPlugin\Models\User;

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
    protected $username;

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
     * @var integer
     */
    protected $must_change_password;

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
     * Method to set the value of field username
     *
     * @param string $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
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
     * Returns the value of field username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
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
     * Returns the value of field must_change_password
     *
     * @return integer
     */
    public function getMustChangePassword()
    {
        return $this->must_change_password;
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
        $this->validate(
            new Email(
                array(
                    "field"    => "email",
                    "required" => true,
                )
            )
        );
        if ($this->validationHasFailed() == true) {
            return false;
        }
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
            'username' => 'username',
            'email' => 'email',
            'password' => 'password',
            'must_change_password' => 'must_change_password',
            'group_id' => 'group_id',
            'banned' => 'banned',
            'suspended' => 'suspended',
            'active' => 'active'
        );
    }
}