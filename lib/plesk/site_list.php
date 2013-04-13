<?php
require_once("plesk_request.php");

class Site_List_Request extends Plesk_Request
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

	protected function process_response($xml)
	{
		$temp = array();
		for($i=0 ;$i<count($xml->domain->get->result); $i++)
		{
			$site = $xml->domain->get->result[$i];
			$temp[] = array(
				'id'=>(string)$site->id,
				'status'=>(string)$site->status,
				'created'=>(string)$site->data->gen_info->cr_date,
				'name'=>(string)$site->data->gen_info->name,
			);
		}
		return $temp;
	}
}