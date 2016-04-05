<?php
namespace pmill\Plesk\Wordpress;

use pmill\Plesk\BaseRequest;
use SimpleXMLElement;

class GetSecurityStatus extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet>
    <wp-instance>
         <get-security-status>
            <filter>
                  <id>{ID}</id>
             </filter>
         </get-security-status>
     </wp-instance>
</packet>
EOT;

    /**
     * @param SimpleXMLElement $xml
     * @return array
     */
    protected function processResponse($xml)
    {
        $response = [];

        foreach ($xml->{'wp-instance'}->{'get-security-status'}->result as $result) {
            $response[] = [
                'status' => (string) $result->status,
                'security-status' => (string) $result->{'security-status'},
            ];
        }

        return $response;
    }
}
