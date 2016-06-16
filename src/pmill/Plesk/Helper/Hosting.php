<?php
namespace pmill\Plesk\Helper;

use pmill\Plesk\Node;
use pmill\Plesk\NodeList;

class Hosting extends NodeGenerationHelper
{
    /**
     * @param $key
     * @param $value
     * @return null|Node
     */
    protected function createNode($key, $value)
    {
        switch ($key) {
            case 'vrt_hst':
                $helper = new VirtualHosting();
                return new Node($key, $helper->generate($value));
            case 'std_fwd':
                $helper = new StandardForwarding();
                return new Node($key, $helper->generate($value));
            default:
                return null;
        }
    }
}
