<?php
namespace pmill\Plesk\Helper;

class Xml
{
    /**
     * @param $node
     * @param $key
     * @param string $node_name
     * @return null|string
     */
    public static function findProperty($node, $key, $node_name = 'property')
    {
        foreach ($node->children() as $property) {
            if ($property->getName() == $node_name && $property->name == $key) {
                return (string)$property->value;
            }
        }

        return null;
    }

    /**
     * @param $node
     * @param string $node_name
     * @return array
     */
    public static function getProperties($node, $node_name = 'property')
    {
        $result = array();

        foreach ($node->children() as $property) {
            if ($property->getName() == $node_name) {
                $result[(string)$property->name] = (string)$property->value;
            }
        }

        return $result;
    }
}
