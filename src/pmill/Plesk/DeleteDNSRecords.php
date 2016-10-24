<?php
namespace pmill\Plesk;

class DeleteDatabase extends BaseRequest
{
    /**
     * @var string
     */
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet>
    <dns>
        <del-rec>
            <filter>
                <id>{ID}</id>
            </filter>
        </del-rec>
    </dns>
</packet>
EOT;

    /**
     * @var array
     */
    protected $default_params = [
        'id' => null,
    ];

    /**
     * @param $xml
     * @return bool
     * @throws ApiRequestException
     */
    protected function processResponse($xml)
    {
        $result = $xml->dns->{'del-rec'}->result;

        if ($result->status == 'error') {
            throw new ApiRequestException($result);
        }

        return true;
    }
}
