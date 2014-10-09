<?php
namespace pmill\Plesk;

class DeleteDomainAlias extends BaseRequest
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
        if ($xml->domain_alias->delete->result->status == 'error')
            return FALSE;
        return TRUE;
    }
}
