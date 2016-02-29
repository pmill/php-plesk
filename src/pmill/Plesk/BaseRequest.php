<?php
namespace pmill\Plesk;

use SimpleXMLElement;

abstract class BaseRequest
{
    /**
     * @var null
     */
    protected $curl;

    /**
     * @var array
     */
    protected $config = array();

    /**
     * @var array
     */
    protected $params = array();

    /**
     * @var array
     */
	protected $default_params = array();

    /**
     * @var array
     */
	protected $node_mapping = array();

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
     * @param $config
     * @param array $params
     * @throws ApiRequestException
     */
    public function __construct($config, $params=array())
    {
        $this->config = $config;
        $this->params = $params;

        if (!$this->check_params()) {
    		throw new ApiRequestException("Error: Incorrect request parameters submitted");
    	}

        $this->init();

        if (is_null($this->xml_packet) && file_exists($this->xml_filename)) {
            $this->xml_packet = file_get_contents($this->xml_filename);
        }

        if (is_null($this->xml_packet)) {
        	throw new ApiRequestException("Error: No XML Packet supplied");
        }
    }

    /**
	 * Checks the required parameters were submitted. Optional parameters are specified with a non NULL value in the class declaration
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
     * @return object
     */
    public function process()
    {
        try {
            $response = $this->sendRequest($this->getPacket());
            if ($response !== false) {
                $responseXml = $this->parseResponse($response);
                $this->checkResponse($responseXml);
            }
        } catch (ApiRequestException $e) {
            $this->error = $e;
            return false;
        }

        return $this->processResponse($responseXml);
    }

    /**
     * Inserts the submitted parameters into the xml packet
     * @return string
     */
    protected function getPacket()
    {
        $packet = $this->xml_packet;

        foreach($this->params as $key => $value) {
            if(is_bool($value)) {
            	$value = $value ? 'true' : 'false';
            }
            $packet = str_replace('{'.strtoupper($key).'}', $value, $packet);
        }

        return $packet;
    }

    /**
     * Performs a Plesk API request, returns raw API response text
     */
    private function init()
    {
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_URL, "https://".$this->config['host'].":8443/enterprise/control/agent.php");
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
                "HTTP_AUTH_LOGIN: ".$this->config['username'],
                "HTTP_AUTH_PASSWD: ".$this->config['password'],
                "HTTP_PRETTY_PRINT: TRUE",
                "Content-Type: text/xml"
            )
        );
    }

    /**
     * Performs a Plesk API request, returns raw API response text
     *
     * @param string $packet
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

        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $domdoc->saveHTML());
        $result = curl_exec($this->curl);
        if (curl_errno($this->curl)) {
            $errmsg  = curl_error($this->curl);
            $errcode = curl_errno($this->curl);
            curl_close($this->curl);
            throw new ApiRequestException($errmsg, $errcode);
        }

        $this->request_header = curl_getinfo($this->curl, CURLINFO_HEADER_OUT);

        curl_close($this->curl);
        return $result;
    }

    /**
     * Looks if API responded with correct data
     *
     * @param string $response_string
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
     * @param SimpleXMLElement $response
     * @return void
     * @throws ApiRequestException
     */
    private function checkResponse(SimpleXMLElement $response) {
        if ($response->system->status === 'error') {
            throw new ApiRequestException("Error: " . $response->system->errtext);
        }
    }

    /**
     * @param $node
     * @param $key
     * @param string $node_name
     * @return null|string
     */
	protected function findProperty($node, $key, $node_name='property')
	{
		foreach ($node->children() as $property) {
			if ($property->getName() == $node_name && $property->name == $key) {
				return (string)$property->value;
			}
		}

		return null;
	}

    /**
     * @param $node
     * @param string $node_name
     * @return array
     */
	protected function getProperties($node, $node_name = 'property')
	{
		$result = array();

		foreach ($node->children() as $property)
		{
			if ($property->getName() == $node_name) {
				$result[(string)$property->name] = (string)$property->value;
			}
		}

		return $result;
    }
}
