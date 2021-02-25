<?php

namespace App\Component;

use App\Component\Renderer\JsonRenderer;
use Exception;
use RuntimeException;

/**
 * Class RendererManager
 */
class RendererManager
{
    /**
     * @var array
     */
    private static $container = [
        AbstractRenderer::JSON_RENDER => ['class' => JsonRenderer::class],
    ];

    /**
     * @var AbstractRenderer
     */
    private $render;

    /**
     * Set render
     *
     * @param integer $render
     * @return RendererManager
     * @throws Exception
     */
    public function setRenderer($render)
    {
        if (!isset(self::$container[$render])) {
            throw new RuntimeException('Render is undefined');
        }

        $Render = self::$container[$render]['class'];

        if (!isset(self::$container[$render]['exemplar'])) {
            self::$container[$render]['exemplar'] = new $Render();
        }

        $this->render = self::$container[$render]['exemplar'];

        return $this;
    }

    /**
     * Get render
     *
     * @return AbstractRenderer
     */
    public function getRenderer()
    {
        return $this->render;
    }
}