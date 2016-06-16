<?php
namespace pmill\Plesk\Helper;

use pmill\Plesk\Node;
use pmill\Plesk\NodeList;

class SubscriptionPrefs extends NodeGenerationHelper
{
    /**
     * @param $key
     * @param $value
     * @return null|Node
     */
    protected function createNode($key, $value)
    {
        switch ($key) {
            case 'www':
            case 'stat_ttl':
            case 'outgoing-messages-domain-limit':
                return new Node($key, $value);
            default:
                return null;
        }
    }
}
