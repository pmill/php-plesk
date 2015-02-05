<?php
namespace pmill\Plesk;

class DeleteDatabase extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet>
    <database>
        <del-db>
            <filter>
                <id>{ID}</id>
            </filter>
        </del-db>
    </database>
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
        $result = $xml->database->{'del-db'}->result;

        if ($result->status == 'error')
            throw new ApiRequestException((string)$result->errtext);

        return TRUE;
    }
}
