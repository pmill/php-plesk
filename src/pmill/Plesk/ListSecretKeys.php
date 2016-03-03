<?php
namespace pmill\Plesk;

class ListSecretKeys extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet>
<secret_key>
   <get_info>
      {FILTER}
   </get_info>
</secret_key>
</packet>
EOT;

    /**
     * @param array $config
     * @param array $params
     * @throws ApiRequestException
     */
    public function __construct(array $config, array $params = [])
    {
        $filterNode = new Node('filter');

        if (isset($params['key'])) {
            $filterNode->setValue(new Node('key', $params['key']));
        }

        if (isset($params['keys']) && is_array($params['keys'])) {
            $nodes = [];

            foreach ($params['keys'] as $key) {
                $nodes[] = new Node('key', $key);
            }

            $filterNode->setValue(new NodeList($nodes));
        }

        $params['filter'] = $filterNode;

        parent::__construct($config, $params);
    }

    /**
     * Process the response from Plesk
     * @return array
     */
    protected function processResponse($xml)
    {
        $response = [];

        foreach ($xml->secret_key->get_info->result as $result) {
            if ($result->status == 'ok' && isset($result->key_info)) {
                $response[] = [
                    'status' => (string)$result->status,
                    'key' => (string)$result->key_info->key,
                    'ip_address' => (string)$result->key_info->ip_address,
                    'description' => (string)$result->key_info->description,
                ];
            } elseif ($result->status == 'error') {
                $response[] = [
                    'status' => (string)$result->status,
                    'key' => (string)$result->key,
                    'error' => [
                        'code' => (string)$result->errcode,
                        'description' => (string)$result->errtext,
                    ],
                ];
            }
        }

        return $response;
    }
}
