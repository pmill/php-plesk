<?php
namespace pmill\Plesk;

class ListDatabaseUsers extends BaseRequest
{
    /**
     * @var string
     */
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.4.2.0">
<database>
   <get-db-users>
      <filter>
          <db-id>{DATABASE_ID}</db-id>
      </filter>
   </get-db-users>
</database>
</packet>
EOT;

    /**
     * @var int
     */
    public $id;

    /**
     * @var array
     */
    protected $default_params = [
        'database_id' => null,
    ];

    /**
     * @param $xml
     * @return bool
     * @throws ApiRequestException
     */
    protected function processResponse($xml)
    {
        $result = [];

        if ($xml->database->{'get-db-users'}->result->status == 'error') {
            throw new ApiRequestException($xml->database->{'get-default-user'}->result);
        }

        $users = $xml->database->{'get-db-users'}->result;
        foreach ($users as $user){
          if (isset($user->id)){
            $result[] = [
              'status'=> (string)$user->status,
              'filter-id'=> (int)$user->{'filter-id'},
              'id'=> (int)$user->id,
              'db-id'=> (int)$user->{'db-id'},
              'login'=> (string)$user->login,
              'acl-host'=> (string)$user->acl->host,
              'allow-access-from-ip'=> (string)$user->{'allow-access-from'}->{'ip-address'},
            ];
          }
        }

        return $result;
    }

}
