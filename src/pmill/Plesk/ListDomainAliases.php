<?php
namespace pmill\Plesk;

class ListDomainAliases extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.0.2">
    <domain_alias>
        <get>
            <filter>
                <domain_name>{DOMAIN}</domain_name>
            </filter>
        </get>
    </domain_alias>
</packet>
EOT;

    /**
     * Process the response from Plesk
     * @return array
     */
    protected function processResponse($xml)
    {
        $result = array();
        for ($i=0; $i<count($xml->domain_alias->get->result); $i++)
        {
            if (empty($xml->domain_alias->get->result[$i]->info)) {
                continue;
            }
            else {
                $node = $xml->domain_alias->get->result[$i];
                $result[(int)$node->id] = (string)$node->info->name;
            }
        }
        return $result;
    }
}
