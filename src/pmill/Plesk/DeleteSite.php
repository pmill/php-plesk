<?php
namespace pmill\Plesk;

class DeleteSite extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.3.0">
<site>
	<del>
		<filter>
			<id>{ID}</id>
		</filter>
	</del>
</site>
</packet>
EOT;

	protected $default_params = array(
		'id'=>NULL,
	);

    /**
     * Process the response from Plesk
     * @return bool
     */
    protected function processResponse($xml)
    {
        if ($xml->site->del->result->status == 'error') {
			throw new ApiRequestException((string)$xml->site->del->result->errtext);
		}

        return TRUE;
    }
}
