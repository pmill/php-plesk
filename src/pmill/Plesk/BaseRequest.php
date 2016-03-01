<?php
namespace pmill\Plesk;

use SimpleXMLElement;

abstract class BaseRequest
{
    /**
     * @var HttpRequestContract
     */
    protected $http;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var array
     */
	protected $default_params = [];

    /**
     * @var array
     */
	protected $node_mapping = [];

    /**
     * @var string
     */
	protected $property_template = <<<EOT
<property>
	<name>{KEY}</name>
	<value>{VALUE}</value>
</property>
EOT;

    /**
     * @var string
     */
    public $xml_filename;

    /**
     * @var string
     */
    public $xml_packet;

    /**
     * @var string
     */
    public $request_header;

    /**
     * @var string
     */
    public $error;

    /**
     * @param $xml
     * @return string
     */
    abstract protected function processResponse($xml);

    /**
     * BaseRequest constructor.
     * @param array $config
     * @param array $params
     * @param HttpRequestContract|null $http
     */
    public function __construct(array $config, array $params = [], HttpRequestContract $http = null)
    {
        $this->config = $config;
        $this->params = $params;

        if (!$this->check_params()) {
            throw new ApiRequestException("Error: Incorrect request parameters submitted");
        }

        $this->http = is_null($http) ? new CurlHttpRequest($this->config['host']) : $http;
        if (isset($this->config['username']) && isset($this->config['password'])) {
            $this->http->setCredentials($this->config['username'], $this->config['password']);
        }

        if (is_null($this->xml_packet) && file_exists($this->xml_filename)) {
            $this->xml_packet = file_get_contents($this->xml_filename);
        }

        if (is_null($this->xml_packet)) {
            throw new ApiRequestException("Error: No XML Packet supplied");
        }
    }

    /**
     * Checks the required parameters were submitted. Optional parameters are specified with a non NULL value in the
     * class declaration
     *
     * @return bool
     */
    protected function check_params()
    {
        if (!is_array($this->default_params)) {
            return false;
        }

        foreach ($this->default_params as $key => $value) {
            if (!isset($this->params[$key])) {
                if (is_null($value)) {
                    return false;
                } else {
                    $this->params[$key] = $value;
                }
            }
        }

        return true;
    }

    /**
     * Generates the xml for a standard property list
     *
     * @param array $properties
     * @return string
     */
    protected function generatePropertyList(array $properties)
    {
        $result = array();

        foreach ($properties as $key => $value) {
            $xml = $this->property_template;
            $xml = str_replace("{KEY}", $key, $xml);
            $xml = str_replace("{VALUE}", $value, $xml);
            $result[] = $xml;
        }

        return implode("\r\n", $result);
    }

    /**
     * Generates the xml for a list of nodes
     *
     * @param array $properties
     * @return string
     */
    protected function generateNodeList(array $properties)
    {
        $result = array();

        foreach ($properties as $key => $value) {
            if (isset($this->node_mapping[$key])) {
                $node_name = $this->node_mapping[$key];
                $result[] = "<".$node_name.">".$value."</".$node_name.">";
            }
        }

        return implode("\r\n", $result);
    }

    /**
     * Submits the xml packet to the Plesk server and forwards the response on for processing
     *
     * @return object
     */
    public function process()
    {
        try {
            $response = $this->sendRequest($this->getPacket());
            if ($response !== false) {
                $responseXml = $this->parseResponse($response);
                $this->checkResponse($responseXml);

                return $this->processResponse($responseXml);
            }
        } catch (ApiRequestException $e) {
            $this->error = $e;
        }

        return false;
    }

    /**
     * Inserts the submitted parameters into the xml packet
     *
     * @return string
     */
    protected function getPacket()
    {
        $packet = $this->xml_packet;

        foreach ($this->params as $key => $value) {
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }
            $packet = str_replace('{'.strtoupper($key).'}', htmlspecialchars($value), $packet);
        }

        return $packet;
    }

    /**
     * Performs a Plesk API request, returns raw API response text
     *
     * @param string $packet
     *
     * @return string
     * @throws ApiRequestException
     */
    private function sendRequest($packet)
    {
        $domdoc = new \DomDocument('1.0', 'UTF-8');
        if ($domdoc->loadXml($packet) === false) {
            $this->error = 'Failed to load payload';
            return false;
        }

        $body = $domdoc->saveHTML();
        return $this->http->sendRequest($body);
    }

    /**
     * Looks if API responded with correct data
     *
     * @param string $response_string
     *
     * @return SimpleXMLElement
     * @throws ApiRequestException
     */
    private function parseResponse($response_string)
    {
        $xml = new SimpleXMLElement($response_string);

        if (!is_a($xml, 'SimpleXMLElement')) {
            throw new ApiRequestException("Cannot parse server response: {$response_string}");
        }

        return $xml;
    }

    /**
     * Check data in API response
     *
     * @param SimpleXMLElement $response
     *
     * @return void
     * @throws ApiRequestException
     */
    private function checkResponse(SimpleXMLElement $response)
    {
        if ($response->system->status === 'error') {
            throw new ApiRequestException("Error: " . $response->system->errtext);
        }
    }
}
