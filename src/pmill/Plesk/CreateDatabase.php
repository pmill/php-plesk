<?php
namespace pmill\Plesk;

class CreateDatabase extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.3.0">
    <database>
        <add-db>
            <webspace-id>{SUBSCRIPTION_ID}</webspace-id>
            <name>{NAME}</name>
            <type>{TYPE}</type>
            <db-server-id>{SERVER_ID}</db-server-id>
        </add-db>
    </database>
</packet>
EOT;
    
    /**
    * @var array
    */
    protected $default_params = array(
        'subscription_id' => NULL,
        'server_id' => NULL,
        'name' => NULL,
        'type' => 'mysql'
    );
    
    /**
    * @param string $xml
    * @return bool
    */
    protected function processResponse($xml)
    {
        if ($xml->database->{'add-db'}->result->status == 'error') {
            throw new ApiRequestException((string)$xml->database->{'add-db'}->result->errtext);
        }
        
        $this->id = (int)$xml->database->{'add-db'}->result->id;
        return TRUE;
    }
}