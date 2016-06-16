<?php
namespace pmill\Plesk\Helper;

use pmill\Plesk\Node;
use pmill\Plesk\NodeList;
use pmill\Plesk\ObjectStatus;

class GenSetup extends NodeGenerationHelper
{
    /**
     * @param $key
     * @param $value
     * @return null|Node
     */
    protected function createNode($key, $value)
    {
        switch ($key) {
            case 'status':
                if (ObjectStatus::isValidStatus($value)) {
                    return new Node($key, $value);
                }
                return null;
            case 'name':
            case 'owner-id':
            case 'owner-login':
            case 'owner-guid':
            case 'owner-external-id':
            case 'ip_address':
            case 'guid':
            case 'external-id':
            case 'admin-as-vendor':
                return new Node($key, $value);
            default:
                return null;
        }
    }
}
