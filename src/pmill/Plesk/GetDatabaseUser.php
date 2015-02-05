<?php
namespace pmill\Plesk;

class GetDatabaseUser extends BaseRequest
{
	public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.4.2.0">
<database>
   <get-default-user>
      <filter>
          <db-id>{DATABASE_ID}</db-id>
      </filter>
   </get-default-user>
</database>
</packet>
EOT;

	/**
	 * @var array
	 */
	protected $default_params = array(
		'database_id'	=> NULL,
	);

	/**
    * @param string $xml
    * @return bool
    */
	protected function processResponse($xml)
    {
        if ($xml->database->{'get-default-user'}->result->status == 'error') {
		 	return false;
		}
        
        $this->id = (int)$xml->database->{'get-default-user'}->result->id;
        return TRUE;
    }

}