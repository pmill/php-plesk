<?php
namespace pmill\Plesk;

class DeleteSiteAlias extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.3.5">
    <site-alias>
        <delete>
            <filter>
                {FILTER}
            </filter>
        </delete>
    </site-alias>
</packet>
EOT;
    
    protected $default_params = array(
		'filter'=>NULL,
	);

	public function __construct($config, $params=array())
	{
		if (isset($params['domain'])) {
			$params['filter'] = '<name>'.$params['domain'].'</name>';
		}
        
        if (isset($params['id'])) {
			$params['filter'] = '<id>'.$params['id'].'</id>';
		}

		parent::__construct($config, $params);
    }

    /**
     * Process the response from Plesk
     * @return bool
     */
    protected function processResponse($xml)
    {
        $result = $xml->{'site-alias'}->delete->result;

        if ($result->status == 'error')
            throw new ApiRequestException((string)$result->errtext);

        return TRUE;
    }
}
