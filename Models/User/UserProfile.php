<?php
namespace Phalcon\UserPlugin\Models\User;

use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class UserProfile extends \Phalcon\Mvc\Model
{
    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var integer
     */
    protected $user_id;

    /**
     *
     * @var integer
     */
    protected $picture;

    /**
     *
     * @var string
     */
    protected $birth_date;

    /**
     *
     * @var string
     */
    protected $gender;

    /**
     *
     * @var integer
     */
    protected $home_location_id;

    /**
     *
     * @var integer
     */
    protected $current_location_id;

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
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field user_id
     *
     * @param integer $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Method to set the value of field picture
     *
     * @param integer $picture
     * @return $this
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Method to set the value of field birth_date
     *
     * @param string $birth_date
     * @return $this
     */
    public function setBirthDate($birth_date)
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    /**
     * Method to set the value of field gender
     *
     * @param string $gender
     * @return $this
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Method to set the value of field home_location_id
     *
     * @param integer $home_location_id
     * @return $this
     */
    public function setHomeLocationId($home_location_id)
    {
        $this->home_location_id = $home_location_id;

        return $this;
    }

    /**
     * Method to set the value of field current_locationid
     *
     * @param integer $current_location_id
     * @return $this
     */
    public function setCurrentLocationId($current_location_id)
    {
        $this->current_location_id = $current_location_id;

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
        return $this->id;
    }

    /**
     * Returns the value of field user_id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Returns the value of field picture
     *
     * @return integer
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Returns the value of field birth_date
     *
     * @return string
     */
    public function getBirthDate()
    {
        return $this->birth_date;
    }

    /**
     * Returns the value of field gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Returns the value of field home_location_id
     *
     * @return integer
     */
    public function getHomeLocationId()
    {
        return $this->home_location_id;
    }

    /**
     * Returns the value of field current_location_id
     *
     * @return integer
     */
    public function getCurrentLocationId()
    {
        return $this->current_location_id;
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('user_id', 'Phalcon\UserPlugin\Models\User\User', 'id', array(
            'alias' => 'user',
            'reusable' => true
        ));

        $this->belongsTo('home_location_id', 'Phalcon\UserPlugin\Models\Location\Locations', 'id', array(
            'alias' => 'homeLocation',
            'reusable' => true
        ));

        $this->belongsTo('current_location_id', 'Phalcon\UserPlugin\Models\Location\Locations', 'id', array(
            'alias' => 'currentLocation',
            'reusable' => true
        ));
    }

    public function getSource()
    {
        return 'user_profile';
    }

    /**
     * @return UserProfile[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return UserProfile
     */
    public static function findFirst($parameters = array())
    {
        return parent::findFirst($parameters);
    }

    public function beforeUpdate()
    {
        $this->updated_at = new \Phalcon\Db\RawValue('now()');
    }

    public function beforeCreate()
    {
        $this->created_at = new \Phalcon\Db\RawValue('now()');
    }

    public static function findByRawSql($what, $conditions, $params=null)
    {
        $sql = "SELECT $what FROM user_profile WHERE $conditions";

        $userProfile = new static();
        $pdoResult = $userProfile->getReadConnection()->query($sql, $params);
        $result = new Resultset(null, $userProfile, $pdoResult);
        $result->setHydrateMode(Resultset::HYDRATE_OBJECTS);

        return $result;
    }

}
