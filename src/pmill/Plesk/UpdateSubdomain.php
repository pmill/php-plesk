<?php
namespace pmill\Plesk;

class UpdateSubdomain extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.5.2.0">
    <subdomain>
        <set>
            <filter>
                {FILTER}
            </filter>
            {PROPERTIES}
        </set>
    </subdomain>
</packet> 
EOT;

    protected $default_params = array(
        'filter'=>NULL,
        'properties'=>NULL,
    );

	public function __construct($config, $params)
	{
        if(isset($params['subdomain'])) {
			$params['filter'] = '<name>'.$params['subdomain'].'</name>';
		}
        
        if(isset($params['id'])) {
			$params['filter'] = '<id>'.$params['id'].'</id>';
		}
        
		$properties = array();

		foreach (array('www_root') AS $key) {
			if (isset($params[$key])) {
				$properties[$key] = $params[$key];
			}
		}

		$params['properties'] = $this->generatePropertyList($properties);

		parent::__construct($config, $params);
    }

    /**
     * Process the response from Plesk
     * @return bool
     */
    protected function processResponse($xml)
    {
        $result = $xml->subdomain->set->result;

        if ($result->status == 'error')
            throw new ApiRequestException((string)$result->errtext);

        return TRUE;
    }
}
