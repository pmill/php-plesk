<?php
namespace pmill\Plesk;

class GetDatabaseUser extends BaseRequest
{
    /**
     * @var string
     */
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.7.0">
<database>
   <get-db-users>
      <filter>
          <id>{ID}</id>
      </filter>
   </get-db-users>
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
     * @return bool
     * @throws ApiRequestException
     */
    protected function processResponse($xml)
    {
        if ($xml->database->{'get-db-users'}->result->status == 'error') {
            throw new ApiRequestException($xml->database->{'get-default-user'}->result);
        }

        $user = $xml->database->{'get-db-users'}->result;
        return [
            'status' => (string)$user->status,
            'filter-id' => (int)$user->{'filter-id'},
            'id' => (int)$user->id,
            'db-id' => (int)$user->{'db-id'},
            'login' => (string)$user->login,
            'acl-host' => (string)$user->acl->host,
            'allow-access-from-ip' => (string)$user->{'allow-access-from'}->{'ip-address'},
        ];
    }

}
