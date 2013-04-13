<?php
require_once(__DIR__."/plesk_request.php");
require_once(__DIR__."/site_info.php");

class Create_Domain_Alias_Request extends Plesk_Request
{
	public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.0.2">
	<domain_alias>
		<create>
			<status>0</status>
			<pref>
				<web>1</web>
				<mail>0</mail>
				<tomcat>0</tomcat>
			</pref>
			<domain_id>{DOMAIN_ID}</domain_id>
   			<name>{ALIAS}</name>
		</create>
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
		if($xml->domain_alias->create->result->status == 'error')
			return FALSE;
		return TRUE;
	}
}