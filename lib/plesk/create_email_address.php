<?php
require_once(__DIR__."/plesk_request.php");
require_once(__DIR__."/site_info.php");

class Create_Email_Address_Request extends Plesk_Request
{
	public $xml_packet = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<packet version="1.6.3.5">
	<mail>
		<create>
			<filter>
				<site-id>{DOMAIN_ID}</site-id>
				<mailname>
					<name>{USERNAME}</name>
					<mailbox>
						<enabled>true</enabled>
					</mailbox>
					<password>
						<value>{PASSWORD}</value>
						<type>plain</type>
					</password>
				</mailname>
			</filter>
		</create>
	</mail>
</packet>
EOT;

	public function __construct($config, $params)
	{
		list($username, $domain) = explode("@", $params['email']);

		$request = new Site_Info_Request($config, array('domain'=>$domain));
		$info = $request->process();

		$params['domain_id'] = $info['id'];
		$params['username'] = $username;

		parent::__construct($config, $params);
	}

	protected function process_response($xml)
	{
		if($xml->mail->create->result->status == 'error')
			return FALSE;
		return TRUE;
	}
}