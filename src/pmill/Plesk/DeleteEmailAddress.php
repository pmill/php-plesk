<?php
namespace pmill\Plesk;

class DeleteEmailAddress extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.3.0">
    <mail>
        <remove>
            <filter>
                <site-id>{SITE_ID}</site-id>
                <name>{USERNAME}</name>
            </filter>
        </remove>
    </mail>
</packet>
EOT;

    protected $default_params = array(
        'email' => null,
    );

    public function __construct($config, $params)
    {
        parent::__construct($config, $params);

        if (!filter_var($this->params['email'], FILTER_VALIDATE_EMAIL)) {
            throw new ApiRequestException("Invalid email submitted");
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
        if ($xml->mail->remove->result->status == 'error') {
            return false;
        }

        return true;
    }
}
