<?php
namespace pmill\Plesk\Wordpress;

use pmill\Plesk\ApiRequestException;
use pmill\Plesk\BaseRequest;
use SimpleXMLElement;

class UpdatePlugin extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet>
    <wp-instance>
         <update-plugin>
             <filter>
                  <id>{ID}</id>
             </filter>
             <asset-id>{PLUGIN_ID}</asset-id>
         </update-plugin>
     </wp-instance>
</packet>
EOT;

    /**
     * @var array
     */
    protected $default_params = [
        'id' => null,
        'plugin_id' => null,
    ];

    /**
     * @param SimpleXMLElement $xml
     * @return bool
     * @throws ApiRequestException
     */
    protected function processResponse($xml)
    {
        if ((string) $xml->{'wp-instance'}->{'update-plugin'}->result->status === 'error') {
            throw new ApiRequestException($xml->{'wp-instance'}->{'update-plugin'}->result);
        }

        return true;
    }
}
