<?php
namespace pmill\Plesk\Helper;

use pmill\Plesk\Node;
use pmill\Plesk\NodeList;

class VirtualHosting extends NodeGenerationHelper
{
    /**
     * @param $key
     * @param $value
     * @return null|Node
     */
    protected function createNode($key, $value)
    {
        switch ($key) {
            case 'ip_address':
                return new Node($key, $value);
            default:
                return new Node('property', NodeList::createFromKeyValueArray([
                    'name' => $key,
                    'value' => $value,
                ]));
        }
    }
}
