<?php
namespace pmill\Plesk\Helper;

use pmill\Plesk\Node;
use pmill\Plesk\NodeList;

class HostingPermissions extends NodeGenerationHelper
{
    /**
     * @param $key
     * @param $value
     * @return null|Node
     */
    protected function createNode($key, $value)
    {
        return new Node('permission', NodeList::createFromKeyValueArray([
            'name' => $key,
            'value' => $value,
        ]));
    }
}
