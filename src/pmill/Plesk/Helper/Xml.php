<?php
namespace pmill\Plesk\Helper;

use pmill\Plesk\ApiRequestException;
use pmill\Plesk\Node;
use pmill\Plesk\NodeList;
use SimpleXMLElement;

class Xml
{
    /**
     * @param string $response_string
     *
     * @return SimpleXMLElement
     * @throws ApiRequestException
     */
    public static function convertStringToXml($string)
    {
        $xml = new SimpleXMLElement($string);

        if (!$xml instanceof SimpleXMLElement) {
            throw new ApiRequestException("Cannot parse server response: {$string}");
        }

        return $xml;
    }

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

    /**
     * Generates the xml for a standard property list
     *
     * @param $template
     * @param array $properties
     * @return string
     */
    public static function generatePropertyList($template, array $properties)
    {
        $result = array();

        foreach ($properties as $key => $value) {
            $xml = $template;
            $xml = str_replace("{KEY}", $key, $xml);
            $xml = str_replace("{VALUE}", self::sanitize($value), $xml);
            $result[] = $xml;
        }

        return implode("\r\n", $result);
    }

    /**
     * @param array $nodeMapping
     * @param array $properties
     * @return NodeList
     */
    public static function generateNodeList(array $nodeMapping, array $properties)
    {
        $nodes = [];

        foreach ($properties as $key => $value) {
            if (isset($nodeMapping[$key])) {
                $tag = $nodeMapping[$key];
                $nodes[] = new Node($tag, $value);
            }
        }

        return new NodeList($nodes);
    }

    /**
     * @param $input
     * @return string
     */
    public static function sanitize($input)
    {
        return htmlspecialchars($input);
    }

    /**
     * @param array $array
     * @return array
     */
    public static function sanitizeArray(array $array)
    {
        foreach ($array as &$value) {
            if (is_string($value)) {
                $value = self::sanitize($value);
            }
        }

        return $array;
    }
}
