<?php
require_once(__DIR__."/plesk_request.php");
require_once(__DIR__."/site_info.php");

class Delete_Domain_Alias_Request extends Plesk_Request
{
	public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.0.2">
	<domain_alias>
		<delete>
			<filter>
				<name>{ALIAS}</name>
			</filter>
		</delete>
	</domain_alias>
</packet>
EOT;

	public function __construct($config, $params)
	{
		$request = new Site_Info_Request($config, $params);
		$info = $request->process();
		$params['domain_id'] = $info['id'];

		parent::__construct($config, $params);
	}

	protected function process_response($xml)
	{
		if($xml->domain_alias->delete->result->status == 'error')
			return FALSE;
		return TRUE;
	}
}