<?php
namespace pmill\Plesk\Wordpress;

use pmill\Plesk\BaseRequest;
use SimpleXMLElement;

class ListInstances extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet>
    <wp-instance>
         <get-list>
         </get-list>
     </wp-instance>
</packet>
EOT;

    /**
     * @param SimpleXMLElement $xml
     * @return array
     */
    protected function processResponse($xml)
    {
        $response = array();

        foreach ($xml->{'wp-instance'}->{'get-list'}->result as $result) {
            $result = $result->{'wp-instance'};

            $response[] = [
                'id' => (int) $result->id,
                'url' => (string) $result->url,
                'owner' => (string) $result->owner,
            ];
        }

        return $response;
    }
}
