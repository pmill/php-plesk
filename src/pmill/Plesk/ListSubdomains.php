<?php
namespace pmill\Plesk;

class ListSubdomains extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.0.2">
    <subdomain>
        <get>
            <filter>
                <parent-name>{DOMAIN}</parent-name>
            </filter>
        </get>
    </subdomain>
</packet>
EOT;

    /**
     * Process the response from Plesk
     * @return array
     */
    protected function processResponse($xml)
    {
        $result = array();

        for ($i=0; $i<count($xml->subdomain->get->result); $i++) {
            if (empty($xml->subdomain->get->result[$i]->data)) {
                continue;
            }
            else {
                $node = $xml->subdomain->get->result[$i];
                $result[(int)$node->id] = (string)$node->data->name;
            }
        }
        return array_unique($result);
    }
}
