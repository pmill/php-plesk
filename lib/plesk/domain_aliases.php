<?php
require_once(__DIR__."/plesk_request.php");

class Domain_Aliases_Request extends Plesk_Request
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

	protected function process_response($xml)
	{
		$temp = array();
		for($i=0; $i<count($xml->domain_alias->get->result); $i++)
		{
			$node = $xml->domain_alias->get->result[$i];
			$temp[(int)$node->id] = (string)$node->info->name;
		}
		return $temp;
	}
}