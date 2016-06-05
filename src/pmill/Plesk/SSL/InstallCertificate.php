<?php
namespace pmill\Plesk\SSL;

use pmill\Plesk\ApiRequestException;
use pmill\Plesk\BaseRequest;
use pmill\Plesk\HttpRequestContract;
use pmill\Plesk\Node;
use SimpleXMLElement;

class InstallCertificate extends BaseRequest
{
    /**
     * @var string
     */
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet>
    <certificate>
        <install>
            <name>{NAME}</name>
            {DESTINATION}
            <content>
                <csr>{CSR}</csr>
                <pvt>{PVT}</pvt>
                {CRT}
                {CA}
            </content>
            <ip_address>{IP-ADDRESS}</ip_address>
        </install>
     </certificate>
</packet>
EOT;

    /**
     * @var array
     */
    protected $default_params = [
        'name' => null,
        'csr' => null,
        'pvt' => null,
        'ip-address' => null,
    ];

    /**
     * @param array $config
     * @param array $params
     * @param HttpRequestContract $http
     * @throws ApiRequestException
     */
    public function __construct(array $config, array $params = [], HttpRequestContract $http = null)
    {
        if (isset($params['admin']) && $params['admin'] === true) {
            $params['destination'] = new Node('admin');
        }

        if (isset($params['webspace'])) {
            $params['destination'] = new Node('webspace', $params['webspace']);
        }

        if (!isset($params['destination'])) {
            throw new ApiRequestException('admin or webspace parameter is required');
        }

        if (isset($params['crt'])) {
            $params['crt'] = new Node('crt', $params['crt']);
        } else {
            $params['crt'] = '';
        }

        if (isset($params['ca'])) {
            $params['ca'] = new Node('ca', $params['ca']);
        } else {
            $params['ca'] = '';
        }

        parent::__construct($config, $params, $http);
    }

    /**
     * @param SimpleXMLElement $xml
     * @return bool
     * @throws ApiRequestException
     */
    protected function processResponse($xml)
    {
        if ((string) $xml->{'certificate'}->{'install'}->result->status === 'error') {
            throw new ApiRequestException($xml->{'certificate'}->{'install'}->result);
        }

        return true;
    }
}
