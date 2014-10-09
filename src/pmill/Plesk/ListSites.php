<?php
namespace pmill\Plesk;

class ListSites extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.0.0">
    <domain>
        <get>
            <filter />
            <dataset>
                <hosting/>
            </dataset>
        </get>
    </domain>
</packet>
EOT;

    /**
     * Process the response from Plesk
     * @return array
     */
    protected function processResponse($xml)
    {
        $result = array();

        for ($i=0 ;$i<count($xml->domain->get->result); $i++) {
            $site = $xml->domain->get->result[$i];
            $result[] = array(
                'id'=>(string)$site->id,
                'status'=>(string)$site->status,
                'created'=>(string)$site->data->gen_info->cr_date,
                'name'=>(string)$site->data->gen_info->name,
                'ip' =>(string)$site->data->gen_info->dns_ip_address,
                'type' =>(string)$site->data->gen_info->htype,
            );
        }
        return $result;
    }
}
