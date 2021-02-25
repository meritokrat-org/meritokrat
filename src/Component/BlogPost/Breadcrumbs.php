<?php


namespace App\Component\BlogPost;

use App\Component\BlogPost\Entity\Breadcrumb;
use Doctrine\Common\Collections\ArrayCollection;

class Breadcrumbs extends ArrayCollection
{
    public static function create()
    {
        return new self();
    }

    /**
     * @param array $element
     *
     * @return Breadcrumbs
     */
    public function add($element)
    {
        parent::add(
            Breadcrumb::create(
                $element['text'],
                isset($element['href']) ? $element['href'] : null
            )
        );

        return $this;
    }

    public function render()
    {
        return <<<HTML
<nav aria-label="breadcrumb">
  <ol class="breadcrumb bg-transparent text-white p-1 m-0">
    {$this->renderChildren()}
  </ol>
</nav>
HTML;
    }

    private function renderChildren()
    {
        return implode(
            PHP_EOL,
            $this
                ->map(
                    static function (Breadcrumb $child) {
                        return $child->render();
                    }
                )
                ->toArray()
        );
    }
}