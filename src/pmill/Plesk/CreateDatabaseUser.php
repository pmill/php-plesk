<?php
namespace pmill\Plesk;

class CreateDatabaseUser extends BaseRequest
{
	public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.5.0">
<database>
    <add-db-user>
        {OPTIONS}
        <login>{USERNAME}</login>
        <password>{PASSWORD}</password>
    </add-db-user>
</database>
</packet>
EOT;

	/**
	 * @var array
	 */
	protected $default_params = array(
		'options' => NULL,
		'username' => NULL,
		'password' => NULL
	);
    
    public function __construct($config, $params)
    {   
        if (isset($params['server_id']) && isset($params['subscription_id'])) {
            $params['options'] = '<webspace-id>'.$params['subscription_id'].'</webspace-id><db-server-id>'.$params['server_id'].'</db-server-id>';
        }
        elseif (isset($params['database_id'])) {
            $params['options'] = '<db-id>'.$params['database_id'].'</db-id>';
        }
        
        parent::__construct($config, $params);
    }

	/**
    * @param string $xml
    * @return bool
    */
	protected function processResponse($xml)
    {
        if ($xml->database->{'add-db-user'}->result->status == 'error') {
		 	throw new ApiRequestException((string)$xml->database->{'add-db-user'}->result->errtext);
		}
        
        $this->id = (int)$xml->database->{'add-db-user'}->result->id;
        return TRUE;
    }

}