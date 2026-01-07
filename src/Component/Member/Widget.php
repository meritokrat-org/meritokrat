<?php


namespace App\Component\Member;

use App\Component\ComponentInterface;
use Doctrine\Common\Collections\ArrayCollection;

class Widget implements ComponentInterface
{
    /** @var string */
    private $title;

    /** @var ArrayCollection */
    private $actions;

    /** @var ArrayCollection */
    private $members;

    public function __construct()
    {
        $this->actions = new ArrayCollection();
        $this->members = new ArrayCollection();
    }

    public static function create()
    {
        return new self();
    }

    public function addAction($url, $text)
    {
        $this->actions->set($url, $text);

        return $this;
    }

    public function render()
    {
        $body = sprintf('<div class="fs11 p-2 text-center bg-light">%s</div>', t('Участников еще нет'));

        if ($this->getMembers()->count() > 0) {
            $members = implode(
                PHP_EOL,
                $this
                    ->getMembers()
                    ->map(function (Card $member) {
                        return $member->render();
                    })
                    ->toArray()
            );
            $body    = sprintf('<div class="mt-1" style="display: grid; grid-gap: .25rem; grid-template-columns: repeat(auto-fill, minmax(60px, 1fr))">%s</div>', $members);
        }

        return <<<HTML
<div>
    <div class="row align-middle m-0 p-1 rounded-top bg-secondary" style="font-size: 11px">
        <div class="col m-0 p-0 white fw-bold">{$this->getTitle()}</div>
        <div class="col-3 p-0 m-0 text-end">
            {$this->renderActions()}
        </div>
    </div>
    {$body}
</div>
HTML;
    }

    /**
     * @return ArrayCollection
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @param ArrayCollection|array $members
     * @return Widget
     */
    public function setMembers($members)
    {
        if (!$members instanceof ArrayCollection) {
            $members = new ArrayCollection($members);
        }

        $this->members = $members;

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
     * @return Widget
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    private function renderActions()
    {
        return implode(PHP_EOL, array_map(function ($text, $url) {
            return <<<HTML
<a class="white text-nowrap p-0" href="{$url}" style="text-decoration: none">{$text}</a>
HTML;
        }, $this->actions->getValues(), $this->actions->getKeys()));
    }

    public function addMember()
    {

    }

    private function renderMembers()
    {
        return <<<HTML

HTML;
    }
}