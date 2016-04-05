<?php
namespace pmill\Plesk\Wordpress;

use pmill\Plesk\ApiRequestException;
use pmill\Plesk\BaseRequest;
use SimpleXMLElement;

class SearchThemes extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet>
    <wp-instance>
         <search-themes>
            <term>{QUERY}</term>
         </search-themes>
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
        if ((string) $xml->{'wp-instance'}->{'search-themes'}->result->status == "error") {
            throw new ApiRequestException($xml->{'wp-instance'}->{'search-themes'}->result);
        }

        $response = [];

        foreach ($xml->{'wp-instance'}->{'search-themes'}->result->item as $result) {
            $response[] = [
                'title' => (string) $result->title,
                'id' => (string) $result->id,
            ];
        }

        return $response;
    }
}
