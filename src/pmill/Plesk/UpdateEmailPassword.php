<?php
namespace pmill\Plesk;

class UpdateEmailPassword extends BaseRequest
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

	protected $default_params = array(
		'domain_id'=>NULL,
        'username'=>NULL,
		'password'=>NULL,
	);

    public function __construct($config, $params)
    {
        if (isset($params['email'])) {
            if (!filter_var($params['email'], FILTER_VALIDATE_EMAIL))
                throw new ApiRequestException("Error: Invalid email submitted");

            list($username, $domain) = explode("@", $params['email']);

            $request = new GetSite($config, array('domain'=>$domain));
            $info = $request->process();

            $params['domain_id'] = $info['id'];
            $params['username'] = $username;
        }
        
        parent::__construct($config, $params);
    }

    /**
     * Process the response from Plesk
     * @return bool
     */
    protected function processResponse($xml)
    {
        $result = $xml->mail->update->result;

        if ($result->status == 'error')
            throw new ApiRequestException((string)$result->errtext);

        $this->id = (int)$result->id;
        return TRUE;
    }
}
