<?php
namespace pmill\Plesk;

use pmill\Plesk\Helper\Xml;

class GetTraffic extends BaseRequest
{
    /**
     * @var string
     */
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.3.0">
<webspace>
    <get_traffic>
        {FILTER}
    </get_traffic>
</webspace>
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
        $filterChildNodes = [];

        foreach (['name', 'owner-id', 'owner-login', 'guid', 'id'] as $nodeName) {
            if (isset($params[$nodeName])) {
                if (!is_array($params[$nodeName])) {
                    $params[$nodeName] = [$params[$nodeName]];
                }

                foreach ($params[$nodeName] as $value) {
                    $filterChildNodes[] = new Node($nodeName, $value);
                }
            }
        }

        $filter = [ new Node('filter', new NodeList($filterChildNodes)) ];
        
        if (isset($params['since_date'])) {
            $filter[] = new Node('since_date', $params['since_date']);
        }

        if (isset($params['to_date'])) {
            $filter[] = new Node('to_date', $params['to_date']);
        }

        $params = [
            'filter' => new NodeList($filter)
        ];

        parent::__construct($config, $params);
    }

    /**
     * @param $xml
     * @return array
     */
    protected function processResponse($xml)
    {
        $result = [];

        for ($i = 0; $i < count($xml->webspace->get_traffic->result); $i++) {
            $webspace = $xml->webspace->get_traffic->result[$i];

            $traffic = [];
            foreach ($webspace->traffic as $day) {
                $traffic[] = [
                    'date'          => (string)$day->date,
                    'http_in'       => (int)$day->http_in,
                    'http_out'      => (int)$day->http_out,
                    'ftp_in'        => (int)$day->ftp_in,
                    'ftp_out'       => (int)$day->ftp_out,
                    'smtp_in'       => (int)$day->smtp_in,
                    'smtp_out'      => (int)$day->smtp_out,
                    'pop3_imap_in'  => (int)$day->pop3_imap_in,
                    'pop3_imap_out' => (int)$day->pop3_imap_out
                ];
            }

            $result[] = [
                'id'         => (string)$webspace->id,
                'status'     => (string)$webspace->status,
                'error_code' => (int)$webspace->errcode,
                'error_text' => (string)$webspace->errtext,
                'traffic'    => $traffic,
            ];
        }

        return $result;
    }
}
