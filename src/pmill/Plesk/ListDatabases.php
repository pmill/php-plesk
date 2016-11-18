<?php
namespace pmill\Plesk;

class ListDatabases extends BaseRequest
{
    /**
     * @var string
     */
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet>
<database>
	<get-db>
			{FILTER}
	</get-db>
</database>
</packet>
EOT;

    /**
     * @var array
     */
    protected $default_params = [
        'filter' => '<filter/>',
    ];

    /**
     * @param array $config
     * @param array $params
     * @throws ApiRequestException
     * */
     public function __construct(array $config, $params = [])
     {
         $this->default_params['filter'] = new Node('filter');
         if (isset($params['subscription_id'])) {
             $webspaceIdNode = new Node('webspace-id', $params['subscription_id']);
             $params['filter'] = new Node('filter', $webspaceIdNode);
         }
         if (isset($params['subscription_id'])) {
             $webspaceNameNode = new Node('webspace-name', $params['subscription_name']);
             $params['filter'] = new Node('filter', $webspaceNameNode);
         }
         parent::__construct($config, $params);
     }

    /**
     * @param $xml
     * @return array
     */
    protected function processResponse($xml)
    {
        $result = [];

        foreach ($xml->database->{'get-db'}->children() as $node) {
            if (isset($node->id)){
                $result[] = [
                    'status' => (string)$node->status,
                    'id' => (int)$node->id,
                    'name' => (string)$node->name,
                    'subscription_id' => (int)$node->{'webspace-id'},
                    'db_server_id' => (int)$node->{'db-server-id'},
                    'default_user_id' => (int)$node->{'default-user-id'},
                ];
            }
        }

        return $result;
    }
}
