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
}
