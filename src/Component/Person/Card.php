<?php

namespace App\Component\Person;

use user_data_peer;

/**
 * Class Card
 */
class Card
{
    private $person;

    public function __construct($person)
    {
        $this->person = $person;
    }

    public static function create($person)
    {
        return new self($person);
    }

    public function render()
    {
        return <<<HTML
<div class="card" style="width: 18rem;">
    <div class="card-body">
        <a href="/profile-{$this->getId()}" target="_blank">{$this->getFullName()}</a>
        <span>{$this->person['email']}</span>
    </div>
</div>
HTML;
    }

    private function getId()
    {
        return $this->person['user_id'];
    }

    private function getFullName()
    {
        return sprintf(
            '%s %s',
            $this->person['first_name'],
            $this->person['last_name']
        );
    }
}