<?php

namespace AppBundle\Entity;

/**
 * APIKey.
 */
class APIKey
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var int
     */
    private $lifetime;

    /**
     * @var string
     */
    private $hash;

    /**
     * @var \AppBundle\Entity\User
     */
    private $user;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return APIKey
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set lifetime.
     *
     * @param int $lifetime
     *
     * @return APIKey
     */
    public function setLifetime($lifetime)
    {
        $this->lifetime = $lifetime;

        return $this;
    }

    /**
     * Get lifetime.
     *
     * @return int
     */
    public function getLifetime()
    {
        return $this->lifetime;
    }

    /**
     * Set hash.
     *
     * @param string $hash
     *
     * @return APIKey
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash.
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set user.
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return APIKey
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function __construct()
    {
        $this->date = new \DateTime('now');
        $this->lifetime = 3600 * 24;
        $this->hash = uniqid();
    }

    public function isValid()
    {
        $tmpDate = $this->date;
        if ($tmpDate->modify('+'.$this->lifetime.' seconds') < new \DateTime('now')) {
            return false;
        }

        return true;
    }
}
