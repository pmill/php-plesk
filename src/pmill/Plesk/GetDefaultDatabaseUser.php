<?php
namespace pmill\Plesk;

class GetDefaultDatabaseUser extends BaseRequest
{
    /**
     * @var string
     */
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
        if ($xml->database->{'get-default-user'}->result->status == 'error') {
            throw new ApiRequestException($xml->database->{'get-default-user'}->result);
        }

        $this->id = (int)$xml->database->{'get-default-user'}->result->id;
        return true;
    }

}
