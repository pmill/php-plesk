<?php
require_once(__DIR__."/plesk_request.php");
require_once(__DIR__."/site_info.php");

class Change_Email_Password_Request extends Plesk_Request
{
	public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.0.2">
	<mail>
		<update>
			<add>
				<filter>
					<domain_id>{DOMAIN_ID}</domain_id>
					<mailname>
						<name>{USERNAME}</name>
						<password>{PASSWORD}</password>
					</mailname>
				</filter>
			</add>
		</update>
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
		if($xml->mail->update->result->status == 'error')
			return FALSE;
		return TRUE;
	}
}