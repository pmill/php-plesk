<?php
namespace pmill\Plesk;

class CreateSession extends BaseRequest
{
    /**
     * @var string
     */
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet>
<server>
   <create_session>
      <login>{USERNAME}</login>
      <data>
        <user_ip>{USER_IP}</user_ip>
        <source_server>{SOURCE_SERVER}</source_server>
      </data>
   </create_session>
</server>
</packet>
EOT;

    /**
     * @var array
     */
    protected $default_params = [
        'username' => null,
        'user_ip' => null,
        'source_server' => null,
    ];

    /**
     * @var string
     */
    public $id;

    /**
     * @param array $config
     * @param array $params
     */
    public function __construct(array $config, array $params = [])
    {
        $params['nodes'] = $this->generateNodeList($params);
        parent::__construct($config, $params);
    }

    /**
     * @param $xml
     * @return bool
     * @throws ApiRequestException
     */
    protected function processResponse($xml)
    {
        $result = $xml->server->create_session->result;

        if ($result->status == 'error') {
            throw new ApiRequestException($result);
        }

        $this->id = (string)$xml->{'server'}->create_session->result->id;
        return true;
    }
}
