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
     * @var int
     */
    private $totalvotes;

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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $votes;

    /**
     * @var \AppBundle\Entity\User
     */
    private $author;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->votes = new \Doctrine\Common\Collections\ArrayCollection();

        $this->date = new \DateTime('NOW');
        $this->upvotes = 0;
        $this->downvotes = 0;
        $this->totalvotes = 0;
    }

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
     * Set upvotes.
     *
     * @param int $upvotes
     *
     * @return JokePost
     */
    public function setUpvotes($upvotes)
    {
        $this->upvotes = $upvotes;

        return $this;
    }

    /**
     * Get upvotes.
     *
     * @return int
     */
    public function getUpvotes()
    {
        return $this->upvotes;
    }

    /**
     * Set downvotes.
     *
     * @param int $downvotes
     *
     * @return JokePost
     */
    public function setDownvotes($downvotes)
    {
        $this->downvotes = $downvotes;

        return $this;
    }

    /**
     * Get downvotes.
     *
     * @return int
     */
    public function getDownvotes()
    {
        return $this->downvotes;
    }

    /**
     * Set totalvotes.
     *
     * @param int $totalvotes
     *
     * @return JokePost
     */
    public function setTotalvotes($totalvotes)
    {
        $this->totalvotes = $totalvotes;

        return $this;
    }

    /**
     * Get totalvotes.
     *
     * @return int
     */
    public function getTotalvotes()
    {
        return $this->totalvotes;
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

    /**
     * Add vote.
     *
     * @param \AppBundle\Entity\Vote $vote
     *
     * @return JokePost
     */
    public function addVote(\AppBundle\Entity\Vote $vote)
    {
        $this->votes[] = $vote;

        return $this;
    }

    /**
     * Remove vote.
     *
     * @param \AppBundle\Entity\Vote $vote
     */
    public function removeVote(\AppBundle\Entity\Vote $vote)
    {
        $this->votes->removeElement($vote);
    }

    /**
     * Get votes.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * Set author.
     *
     * @param \AppBundle\Entity\User $author
     *
     * @return JokePost
     */
    public function setAuthor(\AppBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author.
     *
     * @return \AppBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    public function voteUp()
    {
        ++$this->upvotes;
        ++$this->totalvotes;
    }

    public function voteDown()
    {
        ++$this->downvotes;
        ++$this->totalvotes;
    }

    public function voteUpToDown()
    {
        --$this->upvotes;
        ++$this->downvotes;
    }

    public function voteDownToUp()
    {
        --$this->downvotes;
        ++$this->upvotes;
    }
}
