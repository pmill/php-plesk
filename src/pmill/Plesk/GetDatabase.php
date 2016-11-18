<?php
namespace pmill\Plesk;

class GetDatabase extends BaseRequest
{
    /**
     * @var string
     */
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet>
<database>
	<get-db>
		<filter>
			<id>{ID}</id>
		</filter>
	</get-db>
</database>
</packet>
EOT;

    /**
     * @var array
     */
    protected $default_params = [
        'id' => null,
    ];

    /**
     * @param $xml
     * @return array
     */
    protected function processResponse($xml)
    {
       $db = $xml->db->{'get-db'}->result;
       if ((string)$db->status == 'error') {
           throw new ApiRequestException($db);
                                                }
       if ((string)$db->result->status == 'error') {
           throw new ApiRequestException($db->result);
                                                }
       return [
                'status' => (string)$node->status,
                'id' => (int)$node->id,
                'name' => (string)$node->name,
                'subscription_id' => (int)$node->{'webspace-id'},
                'db_server_id' => (int)$node->{'db-server-id'},
                'default_user_id' => (int)$node->{'default-user-id'},
            ];
    }
}
