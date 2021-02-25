<?php

namespace App\Component;

use Exception;

/**
 * Class Render
 */
abstract class AbstractRenderer implements RendererInterface
{
    const JSON_RENDER = 1;

    const HTTP_OK        = 200;
    const HTTP_NOT_FOUND = 404;
    /**
     * @var array
     */
    protected $data = [];
    /**
     * @var int
     */
    private $httpCode = self::HTTP_OK;
    /**
     * @var array
     */
    private $header = [];

    /**
     * Set header
     *
     * @param string $option
     * @param string $value
     * @return $this
     */
    public function setHeader($option, $value)
    {
        $this->header[$option] = $value;

        return $this;
    }

    /**
     * Assign data
     *
     * @param array $data
     * @return $this
     * @throws Exception
     */
    public function assign($data)
    {
        if (is_array($data)) {
            $this->data = array_merge($this->data, $data);
        } else {
            $this->data = $data;
        }

        return $this;
    }

    public function render()
    {
        http_response_code($this->httpCode);

        foreach ($this->header as $option => $value) {
            header(sprintf('%s: %s', $option, $value));
        }

        return '';
    }
}