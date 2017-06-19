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
    private $choice;

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
     * Set choice.
     *
     * @param int $choice
     *
     * @return Vote
     */
    public function setChoice($choice)
    {
        $this->choice = $choice;

        return $this;
    }

    /**
     * Get choice.
     *
     * @return int
     */
    public function getChoice()
    {
        return $this->choice;
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
