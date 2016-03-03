<?php
namespace pmill\Plesk;

class ListIPAddresses extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.3.0">
<ip>
	<get/>
</ip>
</packet>
EOT;

    /**
     * Process the response from Plesk
     * @return array
     */
    protected function processResponse($xml)
    {
        if ((string)$xml->ip->get->result->status == 'error') {
            throw new ApiRequestException($xml->ip->get->result);
        }

        $result = array();

        foreach ($xml->ip->get->result->addresses->children() AS $ip) {
            $result[] = array(
                'ip_address' => (string)$ip->ip_address,
                'netmask' => (string)$ip->netmask,
                'type' => (string)$ip->type,
                'interface' => (string)$ip->interface,
                'is_default' => isset($ip->default),
            );
        }

        return $result;
    }
}
