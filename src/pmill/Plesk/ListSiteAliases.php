<?php
namespace pmill\Plesk;

class ListSiteAliases extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.3.0">
<site-alias>
    <get>
        {FILTER}
    </get>
</site-alias>
</packet>
EOT;

    protected $default_params = array(
        'filter' => null,
    );

    public function __construct($config, $params = array())
    {
        $params['filter'] = new Node('filter');

        if (isset($params['domain'])) {
            $childNode = new Node('site-name', $params['domain']);
            $params['filter'] = new Node('filter', $childNode);
        }

        if (isset($params['site_id'])) {
            $childNode = new Node('site-id', $params['site_id']);
            $params['filter'] = new Node('filter', $childNode);
        }

        parent::__construct($config, $params);
    }

    /**
     * Process the response from Plesk
     * @return array
     */
    protected function processResponse($xml)
    {
        $result = array();
        foreach ($xml->{"site-alias"}->get->result AS $alias) {
            $result[(int)$alias->id] = (string)$alias->info->name;
        }
        return $result;
    }
}
