<?php
namespace pmill\Plesk\APS;

use pmill\Plesk\ApiRequestException;
use pmill\Plesk\BaseRequest;
use pmill\Plesk\Helper\MailPreferences;
use pmill\Plesk\HttpRequestContract;
use pmill\Plesk\Node;
use pmill\Plesk\NodeList;
use SimpleXMLElement;

class InstallApplication extends BaseRequest
{
    /**
     * @var string
     */
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet>
    <aps>
        <install>
            {OPTIONS}
            <package-id>{PACKAGE-ID}</package-id>
        </install>
     </aps>
</packet>
EOT;

    /**
     * @var array
     */
    protected $default_params = [
        'package-id' => null,
    ];

    /**
     * @param array $config
     * @param array $params
     * @param HttpRequestContract|null $http
     */
    public function __construct(array $config, array $params = [], HttpRequestContract $http = null)
    {
        $optionsNodes = [];
        $optionsNodes[] = $this->getIdentifierNode($params);

        if ($databaseNode = $this->getDatabaseNode($params)) {
            $optionsNodes[] = $databaseNode;
        }

        if ($settingsNode = $this->getSettingsNode($params)) {
            $optionsNodes[] = $settingsNode ;
        }

        if (isset($params['ssl'])) {
            $optionsNodes[] = new Node('ssl', $params['ssl']);
        }

        if (isset($params['url-prefix'])) {
            $optionsNodes[] = new Node('url-prefix', $params['url-prefix']);
        }

        $params['options'] = new NodeList($optionsNodes);

        parent::__construct($config, $params, $http);
    }

    /**
     * @param SimpleXMLElement $xml
     * @return bool
     * @throws ApiRequestException
     */
    protected function processResponse($xml)
    {
        if ((string) $xml->{'aps'}->{'install'}->result->status === 'error') {
            throw new ApiRequestException($xml->{'aps'}->{'install'}->result);
        }

        return true;
    }

    /**
     * @param array $params
     * @return null|Node
     */
    protected function getIdentifierNode(array $params)
    {
        foreach (['domain-id', 'domain-name', 'subdomain-id', 'subdomain-name'] as $key) {
            if (isset($params[$key])) {
                return new Node($key, $params[$key]);
            }
        }

        return null;
    }

    /**
     * @param array $params
     * @return null|Node
     */
    protected function getDatabaseNode(array $params)
    {
        if (isset($params['database']) && is_array($params['database'])) {
            $nodes = [];

            foreach (['name', 'login', 'password', 'prefix', 'server'] as $key) {
                if (isset($params['database'][$key])) {
                    $nodes[] = new Node($key, $params['database'][$key]);
                }
            }

            return new Node('database', $nodes);
        }

        return null;
    }

    /**
     * @param array $params
     * @return null|Node
     */
    protected function getSettingsNode(array $params)
    {
        if (isset($params['settings']) && is_array($params['settings'])) {
            $nodes = [];

            foreach ($params['settings'] as $key => $value) {
                $nodes[] = new Node($key, $value);
            }

            return new Node('settings', $nodes);
        }

        return null;
    }
}
