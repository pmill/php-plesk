<?php
namespace pmill\Plesk\Helper;

use pmill\Plesk\Node;
use pmill\Plesk\NodeList;

class PHPSettings extends NodeGenerationHelper
{
    /**
     * @param $key
     * @param $value
     * @return null|Node
     */
    protected function createNode($key, $value)
    {
        return new Node('setting', NodeList::createFromKeyValueArray([
            'name' => $key,
            'value' => $value,
        ]));
    }
}
