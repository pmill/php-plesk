<?php
namespace pmill\Plesk;

class SetDefaultDatabaseUser extends BaseRequest
{
    /**
     * @var string
     */
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.7.0">
<database>
   <set-default-user>
        <db-id>{DATABASE_ID}</db-id>
        <default-user-id>{ID}</default-user-id>
   </set-default-user>
</database>
</packet>
EOT;

    /**
     * @var array
     */
    protected $default_params = [
        'database_id' => null,
        'id' => null,
    ];

    /**
     * @param $xml
     * @return bool
     * @throws ApiRequestException
     */
    protected function processResponse($xml)
    {
        if ($xml->database->{'set-default-user'}->result->status == 'error') {
            throw new ApiRequestException($xml->database->{'set-default-user'}->result);
        }

        return true;

    }

}
