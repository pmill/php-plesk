<?php
namespace pmill\Plesk;

class CreateEmailAddress extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<packet version="1.6.3.5">
    <mail>
        <create>
            <filter>
                <site-id>{SITE_ID}</site-id>
                <mailname>
                    <name>{USERNAME}</name>
                    <mailbox>
                        <enabled>{ENABLED}</enabled>
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

    protected $default_params = array(
        'email' => null,
        'password' => null,
        'enabled' => true,
    );

    public function __construct($config, $params)
    {
        parent::__construct($config, $params);

        if (!filter_var($this->params['email'], FILTER_VALIDATE_EMAIL)) {
            throw new ApiRequestException("Error: Invalid email submitted");
        }

        list($username, $domain) = explode("@", $this->params['email']);

        $request = new GetSite($config, array('domain' => $domain));
        $info = $request->process();

        $this->params['site_id'] = $info['id'];
        $this->params['username'] = $username;
    }

    /**
     * Process the response from Plesk
     * @return bool
     */
    protected function processResponse($xml)
    {
        $result = $xml->mail->create->result;

        if ($result->status == 'error') {
            throw new ApiRequestException($result);
        }

        $this->id = (int)$result->mailname->id;
        return true;
    }
}
