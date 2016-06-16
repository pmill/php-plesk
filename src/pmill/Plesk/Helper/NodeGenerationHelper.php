<?php
namespace pmill\Plesk\Helper;

use pmill\Plesk\Node;
use pmill\Plesk\NodeList;

abstract class NodeGenerationHelper
{
    /**
     * @param array $params
     * @return NodeList
     */
    public function generate(array $params)
    {
        $nodes = [];
        foreach ($params as $key => $value) {
            if ($node = $this->createNode($key, $value)) {
                $nodes[] = $node;
            }
        }

        return new NodeList($nodes);
    }

    /**
     * @param $key
     * @param $value
     * @return null|Node
     */
    abstract protected function createNode($key, $value);
}
