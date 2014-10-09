<?php
namespace pmill\Plesk;

class DeleteEmailAddress extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.0.2">
    <mail>
        <remove>
            <filter>
                <domain_id>{DOMAIN_ID}</domain_id>
                <name>{USERNAME}</name>
            </filter>
        </remove>
    </mail>
</packet>
EOT;

    public function __construct($config, $params)
    {
        list($username, $domain) = explode("@", $params['email']);

        $request = new GetSiteInfo($config, array('domain'=>$domain));
        $info = $request->process();

        $params['domain_id'] = $info['id'];
        $params['username'] = $username;

        parent::__construct($config, $params);
    }

    /**
     * Process the response from Plesk
     * @return bool
     */
    protected function processResponse($xml)
    {
        if ($xml->mail->remove->result->status == 'error')
            return FALSE;
        return TRUE;
    }
}
