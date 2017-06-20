<?php

namespace AppBundle\Entity;

/**
 * Vote
 */
class Vote
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $up;

    /**
     * @var boolean
     */
    private $down;

    /**
     * @var \AppBundle\Entity\JokePost
     */
    private $jokepost;

    /**
     * @var \AppBundle\Entity\User
     */
    private $user;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set up
     *
     * @param boolean $up
     *
     * @return Vote
     */
    public function setUp($up)
    {
        $this->up = $up;

        return $this;
    }

    /**
     * Get up
     *
     * @return boolean
     */
    public function getUp()
    {
        return $this->up;
    }

    /**
     * Set down
     *
     * @param boolean $down
     *
     * @return Vote
     */
    public function setDown($down)
    {
        $this->down = $down;

        return $this;
    }

    /**
     * Get down
     *
     * @return boolean
     */
    public function getDown()
    {
        return $this->down;
    }

    /**
     * Set jokepost
     *
     * @param \AppBundle\Entity\JokePost $jokepost
     *
     * @return Vote
     */
    public function setJokepost(\AppBundle\Entity\JokePost $jokepost = null)
    {
        $this->jokepost = $jokepost;

        return $this;
    }

    /**
     * Get jokepost
     *
     * @return \AppBundle\Entity\JokePost
     */
    public function getJokepost()
    {
        return $this->jokepost;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Vote
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}

