<?php
namespace pmill\Plesk;

class DeleteSubdomain extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.5.2.0">
    <subdomain>
        <del>
            <filter>
                {FILTER}
            </filter>
        </del>
    </subdomain>
</packet>
EOT;

	protected $default_params = array(
		'filter'=>NULL,
	);

	public function __construct($config, $params=array())
	{
		if (isset($params['subdomain'])) {
            $params['filter'] = new Node('name', $params['subdomain']);
		}
        
        if (isset($params['id'])) {
            $params['filter'] = new Node('id', $params['id']);
		}

		parent::__construct($config, $params);
    }

    /**
     * Process the response from Plesk
     * @return bool
     */
    protected function processResponse($xml)
    {
        $result = $xml->subdomain->del->result;

        if ($result->status == 'error')
            throw new ApiRequestException((string)$result->errtext);

        return TRUE;
    }
}
