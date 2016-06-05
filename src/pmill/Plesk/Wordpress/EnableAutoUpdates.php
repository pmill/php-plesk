<?php
namespace pmill\Plesk\Wordpress;

use pmill\Plesk\ApiRequestException;
use pmill\Plesk\BaseRequest;
use SimpleXMLElement;

class EnableAutoUpdates extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet>
    <wp-instance>
         <enable-auto-updates>
             <filter>
                  <id>{ID}</id>
             </filter>
         </enable-auto-updates>
     </wp-instance>
</packet>
EOT;

    /**
     * @var array
     */
    protected $default_params = [
        'id' => null,
    ];

    /**
     * @param SimpleXMLElement $xml
     * @return bool
     * @throws ApiRequestException
     */
    protected function processResponse($xml)
    {
        if ((string) $xml->{'wp-instance'}->{'enable-auto-updates'}->result->status === 'error') {
            throw new ApiRequestException($xml->{'wp-instance'}->{'enable-auto-updates'}->result);
        }

        return true;
    }
}
