<?php
namespace pmill\Plesk;

class ListDatabases extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet>
<database>
	<get-db>
		<filter>
			<webspace-id>{SUBSCRIPTION_ID}</webspace-id>
		</filter>
	</get-db>
</database>
</packet>
EOT;

	protected $default_params = array(
        'subscription_id'=>NULL,
	);

    /**
     * Process the response from Plesk
     * @return array
     */
    protected function processResponse($xml)
    {
        $result = array();
        foreach($xml->database->{'get-db'}->children() AS $node)
        {
        	$result[] = array(
        		'status'=>(string)$node->status,
        		'id'=>(int)$node->id,
        		'name'=>(string)$node->name,
        		'subscription_id'=>(int)$node->{'webspace-id'},
                'db_server_id'=>(int)$node->{'db-server-id'},
        		'default_user_id'=>(int)$node->{'default-user-id'},
        	);
        }
        
        return $result;
    }
}
