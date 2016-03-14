<?php
namespace pmill\Plesk\APS;

use pmill\Plesk\ApiRequestException;
use pmill\Plesk\BaseRequest;
use pmill\Plesk\HttpRequestContract;
use pmill\Plesk\Node;
use pmill\Plesk\NodeList;
use SimpleXMLElement;

class ListAvailablePackages extends BaseRequest
{
    /**
     * @var string
     */
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet>
    <aps>
         <get-packages-list>
            {FILTER}
         </get-packages-list>
     </aps>
</packet>
EOT;

    /**
     * @param array $config
     * @param array $params
     * @param HttpRequestContract $http
     * @throws ApiRequestException
     */
    public function __construct(array $config, array $params = [], HttpRequestContract $http = null)
    {
        $filterNode = new Node('filter');

        if (isset($params['package-id'])) {
            if (!is_array($params['package-id'])) {
                $params['package-id'] = [$params['package-id']];
            }

            $idNodes = [];
            foreach ($params['package-id'] as $id) {
                $idNodes[] = new Node('package-id', $id);
            }

            $filterNode->setValue(new Node('package-id', new NodeList($idNodes)));
        }

        $params['filter'] = $filterNode;

        parent::__construct($config, $params, $http);
    }

    /**
     * @param SimpleXMLElement $xml
     * @return array
     * @throws ApiRequestException
     */
    protected function processResponse($xml)
    {
        $response = [];

        foreach ($xml->{'aps'}->{'get-packages-list'}->result as $result) {
            if ((string) $result->status == "error") {
                $response[] = [
                    'status' => (string) $result->status,
                    'filter-id' => (int) $result->{'filter-id'},
                    'errcode' => (string) $result->errcode,
                    'errtext' => (string) $result->errtext,
                ];
            } else {
                $response[] = [
                    'status' => (string) $result->status,
                    'id' => (int) $result->package->id,
                    'filter-id' => (int) $result->{'filter-id'},
                    'title' => (string) $result->package->title,
                    'version' => (string) $result->package->version,
                    'release' => (string) $result->package->release,
                    'vendor' => (string) $result->package->vendor,
                    'packager' => (string) $result->package->packager,
                    'is_uploaded' => (int) $result->package->is_uploaded === 1,
                    'is_visible' => (int) $result->package->is_visible === 1,
                    'global_settings_not_set' => (int) $result->package->global_settings_not_set === 1,
                ];
            }
        }

        return $response;
    }
}
