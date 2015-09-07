<?php

namespace Phalcon\UserPlugin\Models\User;

/**
 * Phalcon\UserPlugin\Models\User\UserPermissions.
 */
class UserPermissions extends \Phalcon\Mvc\Model
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $group_id;

    /**
     * @var string
     */
    protected $resource;

    /**
     * @var string
     */
    protected $action;

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
     * Method to set the value of field group_id.
     *
     * @param int $group_id
     *
     * @return $this
     */
    public function setGroupId($group_id)
    {
        $this->group_id = $group_id;

        return $this;
    }

    /**
     * Method to set the value of field resource.
     *
     * @param string $resource
     *
     * @return $this
     */
    public function setResource($resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Method to set the value of field action.
     *
     * @param string $action
     *
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;

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
     * Returns the value of field group_id.
     *
     * @return int
     */
    public function getGroupId()
    {
        return $this->group_id;
    }

    /**
     * Returns the value of field resource.
     *
     * @return string
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Returns the value of field action.
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    public function getSource()
    {
        return 'user_permissions';
    }

    public function initialize()
    {
        $this->belongsTo('group_id', 'Phalcon\UserPlugin\Models\User\UserGroups', 'id', array(
            'alias' => 'group',
            'foreignKey' => array(
                'action' => \Phalcon\Mvc\Model\Relation::ACTION_CASCADE,
            ),
        ));
    }

    /**
     * @return UserPermissions[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return UserPermissions
     */
    public static function findFirst($parameters = array())
    {
        return parent::findFirst($parameters);
    }
}
