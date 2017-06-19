<?php

namespace AppBundle\Entity;

/**
 * Vote.
 */
class Vote
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $type;

    /**
     * @var \AppBundle\Entity\JokePost
     */
    private $jokepost;


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
     * Set type.
     *
     * @param int $type
     *
     * @return Vote
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set jokepost.
     *
     * @param \AppBundle\Entity\JokePost $jokepost
     *
     * @return Comment
     */
    public function setJokepost(\AppBundle\Entity\JokePost $jokepost = null)
    {
        $this->jokepost = $jokepost;

        return $this;
    }

    /**
     * Get jokepost.
     *
     * @return \AppBundle\Entity\JokePost
     */
    public function getJokepost()
    {
        return $this->jokepost;
    }

    /**
     * Set user.
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Comment
     */
    public function setUser(\AppBundle\Entity\User $user = null)
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
}
