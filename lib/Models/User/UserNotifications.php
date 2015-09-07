<?php

namespace Phalcon\UserPlugin\Models\User;

/**
 * Phalcon\UserPlugin\Models\User\UserNotifications.
 */
class UserNotifications extends \Phalcon\Mvc\Model
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $from_user_id;

    /**
     * @var int
     */
    protected $for_user_id;

    /**
     * @var int
     */
    protected $object_id;

    /**
     * @var string
     */
    protected $object_type;

    /**
     * @var string
     */
    protected $notification_type;

    /**
     * @var int
     */
    protected $is_seen;

    /**
     * @var string
     */
    protected $created_at;

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
     * Method to set the value of field from_user_id.
     *
     * @param int $from_user_id
     *
     * @return $this
     */
    public function setFromUserId($from_user_id)
    {
        $this->from_user_id = $from_user_id;

        return $this;
    }

    /**
     * Method to set the value of field for_user_id.
     *
     * @param int $for_user_id
     *
     * @return $this
     */
    public function setForUserId($for_user_id)
    {
        $this->for_user_id = $for_user_id;

        return $this;
    }

    /**
     * Method to set the value of field object_id.
     *
     * @param int $object_id
     *
     * @return $this
     */
    public function setObjectId($object_id)
    {
        $this->object_id = $object_id;

        return $this;
    }

    /**
     * Method to set the value of field object_type.
     *
     * @param int $object_type
     *
     * @return $this
     */
    public function setObjectType($object_type)
    {
        $this->object_type = $object_type;

        return $this;
    }

    /**
     * Method to set the value of field notification_type.
     *
     * @param int $notification_type
     *
     * @return $this
     */
    public function setNotificationType($notification_type)
    {
        $this->notification_type = $notification_type;

        return $this;
    }

    /**
     * Method to set the value of field is_seen.
     *
     * @param int $is_seen
     *
     * @return $this
     */
    public function setIsSeen($is_seen)
    {
        $this->is_seen = $is_seen;

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
     * Returns the value of field id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field from_user_id.
     *
     * @return int
     */
    public function getFromUserId()
    {
        return $this->from_user_id;
    }

    /**
     * Returns the value of field for_user_id.
     *
     * @return int
     */
    public function getForUserId()
    {
        return $this->for_user_id;
    }

    /**
     * Returns the value of field object_id.
     *
     * @return int
     */
    public function getObjectId()
    {
        return $this->object_id;
    }

    /**
     * Returns the value of field object_type.
     *
     * @return int
     */
    public function getObjectType()
    {
        return $this->object_type;
    }

    /**
     * Returns the value of field notification_type.
     *
     * @return string
     */
    public function getNotificationType()
    {
        return $this->notification_type;
    }

    /**
     * Returns the value of field is_seen.
     *
     * @return int
     */
    public function getIsSeen()
    {
        return $this->is_seen;
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('from_user_id', 'Phalcon\UserPlugin\Models\User\User', 'id', array(
            'alias' => 'from_user',
            'reusable' => true,
        ));

        $this->belongsTo('for_user_id', 'Phalcon\UserPlugin\Models\User\User', 'id', array(
            'alias' => 'for_user',
            'reusable' => true,
        ));
    }

    public function getSource()
    {
        return 'user_notifications';
    }

    /**
     * @return UserNotifications[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return UserNotifications
     */
    public static function findFirst($parameters = array())
    {
        return parent::findFirst($parameters);
    }

    public function beforeValidationOnCreate()
    {
        $this->created_at = date('Y-m-d H:i:s');
        $this->is_seen = 0;
    }
}
