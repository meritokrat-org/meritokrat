<?php

namespace App\Component\Renderer;

use App\Component\AbstractRenderer;

/**
 * Class JsonRenderer
 */
class JsonRenderer extends AbstractRenderer
{
    /**
     * @return string
     */
    public function render()
    {
        parent::render();

        return json_encode($this->data);
    }
}