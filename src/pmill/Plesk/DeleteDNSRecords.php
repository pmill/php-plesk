<?php
namespace pmill\Plesk;

class DeleteDNSRecords extends BaseRequest
{
    /**
     * @var string
     */
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet>
    <dns>
        <del-rec>
            <filter>
                <id>{ID}</id>
                <site-id>{ID}</site-id>
            </filter>
        </del-rec>
    </dns>
</packet>
EOT;

    /**
     * @var array
     */
    protected $default_params = [
        'id' => null,
        'site_id' => null,
    ];

    /**
     * @param array $config
     * @param array $params
     * @throws ApiRequestException
     */
    public function __construct($config, $params)
    {
        if (isset($params['domain'])) {
            $request = new GetSite($config, ['domain' => $params['domain']]);
            $info = $request->process();

            $params['site_id'] = $info['id'];
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
        $result = $xml->dns->{'del-rec'}->result;

        if ($result->status == 'error') {
            throw new ApiRequestException($result);
        }

        return true;
    }
}
