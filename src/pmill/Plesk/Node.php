<?php
namespace pmill\Plesk;

use pmill\Plesk\Helper\Xml;

class Node
{
    /**
     * @var string
     */
    protected $tag;

    /**
     * @var string|Node|NodeList
     */
    protected $value;

    /**
     * Node constructor.
     * @param $tag
     * @param null $value
     */
    public function __construct($tag, $value = null)
    {
        $this->tag = $tag;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if (is_null($this->value)) {
            return sprintf('<%s/>', $this->tag);
        }

        if (is_string($this->value)) {
            $this->value = Xml::sanitize($this->value);
        }

        return sprintf('<%s>%s</%s>', $this->tag, (string)$this->value, $this->tag);
    }
}
