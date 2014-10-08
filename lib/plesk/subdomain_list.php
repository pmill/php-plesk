<?php
require_once(__DIR__."/plesk_request.php");

class Subdomain_List_Request extends Plesk_Request
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

	protected function process_response($xml)
	{
		$temp = array();
		for($i=0; $i<count($xml->subdomain->get->result); $i++)
		{
			if(empty($xml->subdomain->get->result[$i]->data))
			{
				continue;
			}
			else
			{
				$node = $xml->subdomain->get->result[$i];
				$temp[(int)$node->id] = (string)$node->data->name;
			}
		}
		return array_unique($temp);
	}
}