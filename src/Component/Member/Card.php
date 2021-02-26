<?php

namespace App\Component\Member;

use App\Component\ComponentInterface;

class Card implements ComponentInterface
{
    const FULL_NAME   = '%s %s';
    const PROFILE_URL = '/profile-%d';
    const PHOTO_URL   = 'https://image.meritokrat.org/%s';

    private $id;
    private $firstName;
    private $lastName;

    public static function create()
    {
        return new self();
    }

    public function render()
    {
        return <<<HTML
<div>
    <a href="{$this->getProfileUrl()}" title="{$this->getFullName()}">
        <img src="{$this->getPhotoUrl()}" class="img-thumbnail w-100" alt="{$this->getFullName()}">
    </a>
</div>
HTML;
    }

    /**
     * @return string
     */
    public function getProfileUrl()
    {
        return sprintf(self::PROFILE_URL, $this->getId());
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
     * @return Card
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return sprintf(self::FULL_NAME, $this->getFirstName(), $this->getLastName());
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     * @return Card
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     * @return Card
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhotoUrl()
    {
        return sprintf(self::PHOTO_URL, \user_helper::photo_path($this->getId(), 's'));
    }
}