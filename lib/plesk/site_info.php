<?php
require_once(__DIR__."/plesk_request.php");

class Site_Info_Request extends Plesk_Request
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

	protected function process_response($xml)
	{
		$node = $xml->domain->get->result;
		if((string)$node->status == 'error')
			throw new Exception((string)$node->errtext);
		if((string)$node->result->status == 'error')
			throw new Exception((string)$node->result->errtext);

		return array(
			'id'=>(string)$node->id,
			'name'=>(string)$node->data->gen_info->name,
			'created'=>(string)$node->data->gen_info->cr_date,
		);
	}
}