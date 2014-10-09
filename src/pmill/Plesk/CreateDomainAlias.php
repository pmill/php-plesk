<?php
namespace pmill\Plesk;

class CreateDomainAlias extends BaseRequest
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
        $request = new GetSiteInfo($config, $params);
        $info = $request->process();
        $params['domain_id'] = $info['id'];

        parent::__construct($config, $params);
    }

    /**
     * Process the response from Plesk
     * @return bool
     */
    protected function processResponse($xml)
    {
        if ($xml->domain_alias->create->result->status == 'error')
            return FALSE;
        return TRUE;
    }
}
