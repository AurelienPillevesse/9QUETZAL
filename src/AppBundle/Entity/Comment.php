<?php

namespace AppBundle\Entity;

/**
 * Comment.
 */
class Comment
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $content;

    /**
     * @var \AppBundle\Entity\JokePost
     */
    private $jokepost;

    /**
     * @var \AppBundle\Entity\User
     */
    private $user;

    /**
     * @var \DateTime
     */
    private $date;

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
     * Set content.
     *
     * @param string $content
     *
     * @return Comment
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
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

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Comment
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

    public function __construct($jokepost = null, $user = null, $content = null)
    {
        $this->jokepost = $jokepost;
        $this->user = $user;
        $this->content = $content;
        $this->date = new \DateTime('now');
    }
}
