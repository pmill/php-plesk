<?php
namespace pmill\Plesk;

use pmill\Plesk\Helper\Xml;

class ListSubdomains extends BaseRequest
{
    /**
     * @var string
     */
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.0.2">
    <subdomain>
        <get>
            <filter/>
        </get>
    </subdomain> 
</packet>
EOT;

    /**
     * @var array
     */
    protected $default_params = array(
        'filter' => '<filter/>',
    );

    /**
     * ListSubdomains constructor.
     * @param array $config
     * @param array $params
     */
    public function __construct(array $config, array $params = [])
    {
        if (isset($params['domain'])) {
            $params['filter'] = '<filter><site-name>' . $params['domain'] . '</site-name></filter>';
        }

        if (isset($params['site_id'])) {
            $params['filter'] = '<filter><site-id>' . $params['site_id'] . '</site-id></filter>';
        }

        parent::__construct($config, $params);
    }

    /**
     * @param $xml
     * @return array
     */
    protected function processResponse($xml)
    {
        $result = array();

        foreach ($xml->subdomain->get->result as $node) {
            $result[] = array(
                'id' => (int)$node->id,
                'status' => (string)$node->status,
                'parent' => (string)$node->data->parent,
                'name' => (string)$node->data->name,
                'php' => (string)Xml::findProperty($node->data, 'php'),
                'php_handler_type' => (string)Xml::findProperty($node->data, 'php_handler_type'),
                'www_root' => (string)Xml::findProperty($node->data, 'www_root'),
            );
        }
        return $result;
    }
}
