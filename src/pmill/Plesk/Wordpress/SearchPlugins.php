<?php
namespace pmill\Plesk\Wordpress;

use pmill\Plesk\ApiRequestException;
use pmill\Plesk\BaseRequest;
use SimpleXMLElement;

class SearchPlugins extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet>
    <wp-instance>
         <search-plugins>
            <term>{QUERY}</term>
         </search-plugins>
     </wp-instance>
</packet>
EOT;

    /**
     * @param SimpleXMLElement $xml
     * @return array
     * @throws ApiRequestException
     */
    protected function processResponse($xml)
    {
        if ((string) $xml->{'wp-instance'}->{'search-plugins'}->result->status == "error") {
            throw new ApiRequestException($xml->{'wp-instance'}->{'search-plugins'}->result);
        }

        $response = [];

        foreach ($xml->{'wp-instance'}->{'search-plugins'}->result->item as $result) {
            $response[] = [
                'title' => (string) $result->title,
                'id' => (string) $result->id,
            ];
        }

        return $response;
    }
}
