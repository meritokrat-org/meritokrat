<?php

namespace App\Model\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Ppo
{
    private $id;

    private $title;

    private $members;

    public function __construct()
    {
        $this->members = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Ppo
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return Ppo
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @param mixed $members
     * @return Ppo
     */
    public function setMembers($members)
    {
        $this->members = $members;

        return $this;
    }

    /**
     * @param $person
     */
    public function addMember($person)
    {
        $this->members->add($person);

        return $this;
    }
}
