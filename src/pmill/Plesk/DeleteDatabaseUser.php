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
                <id>{ID}</id>
                <db-id>{DATABASE_ID}</db-id>
            </filter>
        </del-db-user>
    </database>
</packet>
EOT;

    /**
     * @var array
     */
    protected $default_params = [
        'id' => null,
        'database_id' => null,
    ];

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
