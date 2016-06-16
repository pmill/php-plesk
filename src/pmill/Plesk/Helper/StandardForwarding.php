<?php
namespace pmill\Plesk\Helper;

use pmill\Plesk\Node;

class StandardForwarding extends NodeGenerationHelper
{
    /**
     * @param $key
     * @param $value
     * @return null|Node
     */
    protected function createNode($key, $value)
    {
        switch ($key) {
            case 'dest_url':
            case 'ip_address':
                return new Node($key, $value);
            default:
                return null;
        }
    }
}
