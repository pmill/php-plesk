<?php
namespace pmill\Plesk;

class GetSite extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.0.0">
<domain>
	<get>
		<filter>
			<domain-name>{DOMAIN}</domain-name>
		</filter>
		<dataset>
			<hosting/>
		</dataset>
	</get>
</domain>
</packet>
EOT;

    protected $default_params = array(
        'domain' => null,
    );

    /**
     * Process the response from Plesk
     * @return array
     */
    protected function processResponse($xml)
    {
        $site = $xml->domain->get->result;

        if ((string)$site->status == 'error') {
            throw new ApiRequestException($site);
        }
        if ((string)$site->result->status == 'error') {
            throw new ApiRequestException($site->result);
        }

        $hosting_type = (string)$site->data->gen_info->htype;

        return array(
            'id' => (string)$site->id,
            'status' => (string)$site->status,
            'created' => (string)$site->data->gen_info->cr_date,
            'name' => (string)$site->data->gen_info->name,
            'ip' => (string)$site->data->gen_info->dns_ip_address,
            'hosting_type' => $hosting_type,
            'ip_address' => (string)$site->data->hosting->{$hosting_type}->ip_address,
            'www_root' => $this->findHostingProperty($site->data->hosting->{$hosting_type}, 'www_root'),
            'ftp_username' => $this->findHostingProperty($site->data->hosting->{$hosting_type}, 'ftp_login'),
            'ftp_password' => $this->findHostingProperty($site->data->hosting->{$hosting_type}, 'ftp_password'),
        );
    }

    /*
	 * Helper function to search an XML tree for a specific property
	 * @return string
	 */
    protected function findHostingProperty($node, $key)
    {
        foreach ($node->children() AS $property) {
            if ($property->name == $key) {
                return (string)$property->value;
            }
        }
        return null;
    }
}
