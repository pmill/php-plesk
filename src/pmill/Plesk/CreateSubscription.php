<?php
namespace pmill\Plesk;

class CreateSubscription extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<packet version="1.6.3.0">
<webspace>
	<add>
		<gen_setup>
			<name>{DOMAIN_NAME}</name>
			<ip_address>{IP_ADDRESS}</ip_address>
			<owner-id>{OWNER_ID}</owner-id>
      		<htype>vrt_hst</htype>
      		<status>{STATUS}</status>
		</gen_setup>
		<hosting>
			<vrt_hst>
				<property>
					<name>ftp_login</name>
					<value>{USERNAME}</value>
				</property>
				<property>
					<name>ftp_password</name>
					<value>{PASSWORD}</value>
				</property>
				<ip_address>{IP_ADDRESS}</ip_address>
			</vrt_hst>
		</hosting>
		<plan-id>{SERVICE_PLAN_ID}</plan-id>
	</add>
</webspace>
</packet>
EOT;

    protected $default_params = array(
        'domain_name' => null,
        'ip_address' => null,
        'username' => null,
        'password' => null,
        'owner_id' => null,
        'service_plan_id' => null,
        'status' => 0,
    );

    /**
     * Process the response from Plesk
     * @return bool
     */
    protected function processResponse($xml)
    {
        if ($xml->webspace->add->result->status == 'error') {
            throw new ApiRequestException((string)$xml->webspace->add->result->errtext,
                (int)$xml->webspace->add->result->errcode);
        }

        $this->id = (int)$xml->webspace->add->result->id;
        return true;
    }
}
