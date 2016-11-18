<?php
namespace pmill\Plesk;

class GetDatabaseServer extends BaseRequest
{
    /**
     * @var string
     */
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.7.0">
<db_server>
   <get-local>
      <filter>
          {FILTER}
      </filter>
   </get-local>
</db_server>
</packet>
EOT;

    /**
     * @var array
     */
    protected $default_params = [
        'filter' => null,
    ];

    /**
     * GetDatabaseServer constructor.
     * @param array $config
     * @param array $params
     */
    public function __construct(array $config, $params = [])
    {
        if (isset($params['type'])) {
            $params['filter'] = new Node('type', $params['type']);
        }
        if (isset($params['id'])) {
            $params['filter'] = new Node('id', $params['id']);
        }
        parent::__construct($config, $params);
    }

    /**
     * @param $xml
     * @return bool
     * @throws ApiRequestException
     */
    protected function processResponse($xml)
    {
        if ($xml->db_server->{'get-local'}->result->status == 'error') {
            throw new ApiRequestException($xml->db_server->{'get-local'}->result);
        }

        $db_server = $xml->db_server->{'get-local'}->result;
        return [
            'id' => (int)$db_sever->id,
            'status' => (string)$db_sever->status,
            'type' => (string)$db_server->type,
        ];
    }

}
