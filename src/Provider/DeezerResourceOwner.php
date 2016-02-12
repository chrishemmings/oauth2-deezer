<?php

namespace ChrisHemmings\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class DeezerResourceOwner implements ResourceOwnerInterface
{
    /**
     * Raw response
     *
     * @var
     */
    protected $response;

    /**
     * Creates new resource owner.
     *
     * @param $response
     */
    public function __construct($response)
    {
        $this->response = $response;
    }

    /**
     * Get resource owner id
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->response['id'] ?: null;
    }

    /**
     * Return all of the owner details available as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }

    /**
     * Get name
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->response['name'] ?: null;
    }

    /**
     * Get firstname
     *
     * @return string|null
     */
    public function getFirstname()
    {
        return $this->response['firstname'] ?: null;
    }

    /**
     * Get lastname
     *
     * @return string|null
     */
    public function getLastname()
    {
        return $this->response['lastname'] ?: null;
    }

    /**
     * Get birthday
     *
     * @return string|null
     */
    public function getBirthday()
    {
        return $this->response['birthday'] ?: null;
    }

    /**
     * Get inscription date
     *
     * @return string|null
     */
    public function getInscriptionDate()
    {
        return $this->response['inscription_date'] ?: null;
    }

    /**
     * Get link to account
     *
     * @return string|null
     */
    public function getLink()
    {
        return $this->response['link'] ?: null;
    }

    /**
     * Get gender
     *
     * @return string|null
     */
    public function getGender()
    {
        return $this->response['gender'] ?: null;
    }

    /**
     * Get get profle picture url
     *
     * @return string|null
     */
    public function getPicture()
    {
        return $this->response['picture'] ?: null;
    }

    /**
     * Get small profile picture url
     *
     * @return string|null
     */
    public function getPictureSmall()
    {
        return $this->response['picture_small'] ?: null;
    }

    /**
     * Get medium profile picture url
     *
     * @return string|null
     */
    public function getPictureMedium()
    {
        return $this->response['picture_medium'] ?: null;
    }

    /**
     * Get big profile picture url
     *
     * @return string|null
     */
    public function getPictureBig()
    {
        return $this->response['picture_big'] ?: null;
    }

    /**
     * Get account country
     *
     * @return string|null
     */
    public function getCountry()
    {
        return $this->response['country'] ?: null;
    }

    /**
     * Get account language
     *
     * @return string|null
     */
    public function getLang()
    {
        return $this->response['lang'] ?: null;
    }

    /**
     * Get is_kid user detail
     *
     * @return bool|null
     */
    public function isKid()
    {
        return $this->response['is_kid'] ? (bool) $this->response['is_kid'] : null;
    }

    /**
     * Get tracklist url
     *
     * @return string|null
     */
    public function getTracklist()
    {
        return $this->response['tracklist'] ?: null;
    }

    /**
     * Get account type
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->response['type'] ?: null;
    }
}
