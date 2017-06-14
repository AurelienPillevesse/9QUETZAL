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
}
