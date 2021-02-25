<?php

use App\Component\AbstractRenderer;
use App\Component\RendererManager;

abstract class api_controller extends frontend_controller
{
    /**
     * @var RendererManager
     */
    protected $rendererManager;

    /**
     * @var AbstractRenderer
     */
    protected $renderer;

    /**
     * @var array
     */
    private $directions = [];

    /**
     * Initialization
     *
     * @throws Exception
     */
    public function init()
    {
        parent::init();

        $this->rendererManager = new RendererManager();
        $this->renderer        = $this->rendererManager
            ->setRenderer(AbstractRenderer::JSON_RENDER)
            ->getRenderer();

        $this->renderer->setHeader('Access-Control-Allow-Origin', '*');
    }

    /**
     * Execution
     *
     * @throws Exception
     */
    public function execute()
    {
        $this->renderer->assign($this->invokeDirection(request::get_string('direction')));
    }

    /**
     * @param string $alias
     * @return mixed
     * @throws Exception
     */
    protected function invokeDirection($alias)
    {
        if (!isset($this->directions[$alias])) {
            throw new Exception(sprintf('Unknown direction: %s', $alias));
        }

        /** @var ReflectionMethod $direction */
        $direction = (new ReflectionClass($this))->getMethod($alias);
        $direction->setAccessible(true);

        $arguments = [];

        /** @var ReflectionParameter $parameter */
        foreach ($direction->getParameters() as $parameter) {
            $arguments[] = request::get($parameter->getName());
        }

        return $direction->invokeArgs($this, $arguments);
    }

    /**
     * Json response renderer
     *
     * @return string
     */
    public function render()
    {
        return $this->renderer->render();
    }

    /**
     * Register direction
     *
     * @param string $alias
     * @param null|string $direction
     * @return api_controller
     */
    protected function registerDirection($alias, $direction = null)
    {
        if (empty($direction)) {
            $direction = $alias;
        }

        $this->directions[$alias] = $direction;

        return $this;
    }
}
