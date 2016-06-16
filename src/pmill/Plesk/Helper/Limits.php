<?php
namespace pmill\Plesk\Helper;

use pmill\Plesk\Node;
use pmill\Plesk\NodeList;
use pmill\Plesk\ObjectStatus;

class Limits extends NodeGenerationHelper
{
    /**
     * @param $key
     * @param $value
     * @return null|Node
     */
    protected function createNode($key, $value)
    {
        switch ($key) {
            case 'overuse':
                return new Node($key, $value);
            default:
                return new Node('limit', NodeList::createFromKeyValueArray([
                    'name' => $key,
                    'value' => $value,
                ]));
        }
    }
}
