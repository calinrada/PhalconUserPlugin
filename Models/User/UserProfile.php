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
     * @var string
     */
    protected $birth_date;

    /**
     *
     * @var integer
     */
    protected $home_location;

    /**
     *
     * @var integer
     */
    protected $current_location;

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
     * Method to set the value of field home_location
     *
     * @param integer $home_location
     * @return $this
     */
    public function setHomeLocation($home_location)
    {
        $this->home_location = $home_location;
        return $this;
    }

    /**
     * Method to set the value of field current_location
     *
     * @param integer $current_location
     * @return $this
     */
    public function setCurrentLocation($current_location)
    {
        $this->current_location = $current_location;
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
     * Returns the value of field birth_date
     *
     * @return string
     */
    public function getBirthDate()
    {
        return $this->birth_date;
    }

    /**
     * Returns the value of field home_location
     *
     * @return integer
     */
    public function getHomeLocation()
    {
        return $this->home_location;
    }

    /**
     * Returns the value of field current_location
     *
     * @return integer
     */
    public function getCurrentLocation()
    {
        return $this->current_location;
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

    /**
     * Independent Column Mapping.
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'user_id' => 'user_id',
            'birth_date' => 'birth_date',
            'home_location' => 'home_location',
            'current_location' => 'current_location'
        );
    }

    public function beforeValidationOnCreate()
    {
        //$this->home_location = "GeomFromText('Point(52.5177, -0.0968)')";
        //$this->current_location = "GeomFromText('Point(52.5177, -0.0968)')";
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
