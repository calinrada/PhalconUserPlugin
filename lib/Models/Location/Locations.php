<?php

namespace Phalcon\UserPlugin\Models\Location;

class Locations extends \Phalcon\Mvc\Model
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $language;

    /**
     * @var string
     */
    protected $formatted_address;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $admin_area_level_1;

    /**
     * @var string
     */
    protected $admin_area_level_2;

    /**
     * @var string
     */
    protected $postal_code;

    /**
     * @var string
     */
    protected $country;

    /**
     * @var float
     */
    protected $latitude;

    /**
     * @var float
     */
    protected $longitude;

    /**
     * @var float
     */
    protected $geo_point;

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
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field language.
     *
     * @param string $language
     *
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Method to set the value of field formatted_address.
     *
     * @param string $formatted_address
     *
     * @return $this
     */
    public function setFormattedAddress($formatted_address)
    {
        $this->formatted_address = $formatted_address;

        return $this;
    }

    /**
     * Method to set the value of field city.
     *
     * @param string $city
     *
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Method to set the value of field admin area level 1.
     *
     * @param string $admin_area_level_1
     *
     * @return $this
     */
    public function setAdminAreaLevel1($admin_area_level_1)
    {
        $this->admin_area_level_1 = $admin_area_level_1;

        return $this;
    }

    /**
     * Method to set the value of field admin area level 2.
     *
     * @param string $admin_area_level_2
     *
     * @return $this
     */
    public function setAdminAreaLevel2($admin_area_level_2)
    {
        $this->admin_area_level_2 = $admin_area_level_2;

        return $this;
    }

    /**
     * Method to set the value of field postal code.
     *
     * @param string $postal_code
     *
     * @return $this
     */
    public function setPostalCode($postal_code)
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    /**
     * Method to set the value of field country.
     *
     * @param string $country
     *
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Method to set the value of field latitude.
     *
     * @param float $latitude
     *
     * @return $this
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Method to set the value of field longitude.
     *
     * @param float $longitude
     *
     * @return $this
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Method to set the value of field geo_point.
     *
     * @param float $geo_point
     *
     * @return $this
     */
    public function setGeoPoint($geo_point)
    {
        $this->geo_point = $geo_point;

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
        return $this->id;
    }

    /**
     * Returns the value of field language.
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Returns the value of field formatted_address.
     *
     * @return string
     */
    public function getFormattedAddress()
    {
        return $this->formatted_address;
    }

    /**
     * Returns the value of field city.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Returns the value of field admin area level 1.
     *
     * @return string
     */
    public function getAdminAreaLevel1()
    {
      return $this->admin_area_level_1;
    }

    /**
     * Returns the value of field admin area level 2.
     *
     * @return string
     */
    public function getAdminAreaLevel2()
    {
      return $this->admin_area_level_2;
    }

    /**
     * Returns the value of field postal code.
     *
     * @return string
     */
    public function getPostalCode()
    {
      return $this->postal_code;
    }

    /**
     * Returns the value of field country.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Returns the value of field latitude.
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Returns the value of field longitude.
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
    /**
     * Returns the value of field geo_point.
     *
     * @return float
     */
    public function getGeoPoint()
    {
        return $this->geo_point;
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
     * Initialize method for model.
     */
    public function initialize()
    {
    }

    public function getSource()
    {
        return 'locations';
    }

    /**
     * @return Locations[]
     */
    public static function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @return Locations
     */
    public static function findFirst($parameters = array())
    {
        return parent::findFirst($parameters);
    }

    public function beforeCreate()
    {
        $this->created_at = date('Y-m-d H:i:s');
    }

    public function beforeUpdate()
    {
        $this->updated_at = date('Y-m-d H:i:s');
    }

    /**
     * Return a custom formatted address.
     *
     * @return string
     */
    public function getCustomFormattedAddress()
    {
        return $this->city.', '.$this->country;
    }

    /**
     * Return a custom formatted address.
     *
     * @return string
     */
    public function getCustomFormattedAddressLevel1()
    {
        return $this->city.', '.$this->admin_area_level_1.' '.$this->postal_code.', '.$this->country;
    }

    /**
     * Return a custom formatted address.
     *
     * @return string
     */
    public function getCustomFormattedAddressLevel2()
    {
        return $this->city.', '.$this->admin_area_level_2.', '.$this->admin_area_level_1.' '.$this->postal_code.', '.$this->country;
    }

    /**
     * Return a custom formatted address.
     *
     * @return string
     */
    public function getCustomFormattedAddressAll()
    {
        return $this->city ?: ''.', '.$this->admin_area_level_2 ?: ''.', '.$this->admin_area_level_1 ?: ''.' '.$this->postal_code ?: ''.', '.$this->country ?: '';
    }
}
