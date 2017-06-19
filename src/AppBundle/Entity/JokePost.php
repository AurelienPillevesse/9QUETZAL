<?php

namespace AppBundle\Entity;

/**
 * JokePost.
 */
class JokePost
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var int
     */
    private $upvotes;

    /**
     * @var int
     */
    private $downvotes;

    /**
     * @var string
     */
    private $img;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $comments;

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
     * Set title.
     *
     * @param string $title
     *
     * @return JokePost
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set img.
     *
     * @param string $img
     *
     * @return JokePost
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get img.
     *
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return JokePost
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
     * Set vote.
     *
     * @param int $vote
     *
     * @return JokePost
     */
    public function setUpvotes($upvotes)
    {
        $this->upvotes = $upvotes;

        return $this;
    }

    /**
     * Get vote.
     *
     * @return int
     */
    public function getUpvotes()
    {
        return $this->upvotes;
    }

    /**
     * Set vote.
     *
     * @param int $vote
     *
     * @return JokePost
     */
    public function setDownvotes($downvote)
    {
        $this->downvote = $downvote;

        return $this;
    }

    /**
     * Get vote.
     *
     * @return int
     */
    public function getDownvotes()
    {
        return $this->vote;
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add comment.
     *
     * @param \AppBundle\Entity\Comment $comment
     *
     * @return JokePost
     */
    public function addComment(\AppBundle\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment.
     *
     * @param \AppBundle\Entity\Comment $comment
     */
    public function removeComment(\AppBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }
}
