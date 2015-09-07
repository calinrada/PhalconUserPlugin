<?php

namespace Phalcon\UserPlugin\Models\User;

use Phalcon\Mvc\Model\Validator\Uniqueness;

class UserGroups extends \Phalcon\Mvc\Model
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $active;

    /**
     * Method to set the value of field id.
     *
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

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
     * Method to set the value of field active.
     *
     * @param int $active
     *
     * @return $this
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Returns the value of field id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * Returns the value of field active.
     *
     * @return int
     */
    public function getActive()
    {
        return $this->active;
    }

    public function getSource()
    {
        return 'user_groups';
    }

    /**
     * @return UserGroups[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return UserGroups
     */
    public static function findFirst($parameters = array())
    {
        return parent::findFirst($parameters);
    }

    public function initialize()
    {
        $this->hasMany('id', 'Phalcon\UserPlugin\Models\User\UserPermissions', 'group_id', array(
            'alias' => 'permissions',
        ));
    }

    /**
     * Validations and business logic.
     */
    public function validation()
    {
        $this->validate(new Uniqueness(
            array(
                'field' => 'name',
                'message' => 'Group name already registered',
            )
        ));

        return true !== $this->validationHasFailed();
    }
}
