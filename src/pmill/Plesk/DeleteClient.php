<?php
namespace pmill\Plesk;

class DeleteClient extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.3.0">
<customer>
	<del>
		<filter>
			{FILTER}
		</filter>
	</del>
</customer>
</packet>
EOT;

	protected $default_params = array(
		'filter'=>NULL,
	);

	public function __construct($config, $params=array())
	{
		if(isset($params['username'])) {
			$params['filter'] = '<login>'.$params['username'].'</login>';
		}

		if(isset($params['id'])) {
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
    	$result = $xml->customer->del->result;

        if ($result->status == 'error') {
            throw new ApiRequestException((string)$result->errtext);
        }

        return TRUE;
    }
}
