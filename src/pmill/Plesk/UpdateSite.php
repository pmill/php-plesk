<?php
namespace pmill\Plesk;

class UpdateSite extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.3.5">
<site>
    <set>
        <filter>
            <id>{ID}</id>
        </filter>
        <values>
			{NODES}
			{PROPERTIES}
		</values>
    </set>
</site>
</packet>
EOT;

    protected $default_params = array(
        'id'=>NULL,
        'nodes'=>'',
        'properties'=>'',
    );

	protected $node_mapping = array(
		'status'=>'status',
		'domain'=>'name',
    );

    public function __construct($config, $params=array())
    {
        $properties = array();

		foreach (array('php', 'php_handler_type', 'webstat', 'www_root') AS $key) {
			if (isset($params[$key])) {
				$properties[$key] = $params[$key];
			}
		}

		if (count($properties) > 0) {
			$params['properties'] = '<hosting><vrt_hst>'.$this->generatePropertyList($properties).'</hosting></vrt_hst>';
		}

		$nodes_value = trim($this->generateNodeList($params));

		if (strlen($nodes_value) > 0) {
			$params['nodes'] = '<gen_setup>'.$nodes_value.'</gen_setup>';
		}

        parent::__construct($config, $params);
    }

    /**
     * Process the response from Plesk
     * @return bool
     */
    protected function processResponse($xml)
    {
        if ($xml->site->set->result->status == 'error') {
            throw new ApiRequestException((string)$xml->site->set->result->errtext);
        }

        $this->id = (int)$xml->site->set->result->id;
        return TRUE;
    }
}
