<?php
namespace pmill\Plesk;

class DeleteDatabaseUser extends BaseRequest
{
    /**
     * @var string
     */
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet>
    <database>
        <del-db-user>
            <filter>
                {FILTER}
            </filter>
        </del-db-user>
    </database>
</packet>
EOT;

    /**
     * @var array
     */
    protected $default_params = [
        'filter' => null,
    ];

    /**
     * @param array $config
     * @param array $params
     * @throws ApiRequestException
     */
    public function __construct($config, $params = [])
    {
        if (isset($params['id'])) {
            $params['filter'] = new Node('id', $params['id']);
        }
        else if (isset($params['database_id'])) {
            $params['filter'] = new Node('db-id', $params['database_id']);
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
        $result = $xml->database->{'del-db-user'}->result;

        if ($result->status == 'error') {
            throw new ApiRequestException($result);
        }

        return true;
    }
}
