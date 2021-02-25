<?php


namespace App\Component\BlogPost\Entity;

class Breadcrumb
{
    /** @var string */
    private $text;

    /** @var null|string */
    private $href;

    /** @var bool */
    private $active;

    private function __construct($text, $href = null)
    {
        $this->text   = $text;
        $this->href   = $href;
        $this->active = null === $href;
    }

    public static function create($text, $href = null)
    {
        return new self($text, $href);
    }

    public function render()
    {
        if (!$this->active) {
            return <<<HTML
<li class="breadcrumb-item"><a href="{$this->href}">{$this->text}</a></li>
HTML;
        }

        return <<<HTML
<li class="breadcrumb-item active text-white" aria-current="page">{$this->text}</li>
HTML;
    }
}