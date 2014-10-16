<?php
namespace pmill\Plesk;

abstract class BaseRequest
{
    protected $curl = NULL;
    protected $config = array();
    protected $params = array();
	protected $default_params = array();
	protected $node_mapping = array();
	protected $property_template = <<<EOT
<property>
	<name>{KEY}</name>
	<value>{VALUE}</value>
</property>
EOT;

    public $xml_filename = NULL;
    public $xml_packet = NULL;
    public $request_header = NULL;
    public $error = NULL;

    abstract protected function processResponse($xml);

    public function __construct($config, $params=array())
    {
        $this->config = $config;
        $this->params = $params;

        if (!$this->check_params()) {
    		throw new ApiRequestException("Error: Incorrect request parameters submitted");
    	}

        $this->init();

        if ($this->xml_packet === NULL AND file_exists($this->xml_filename)) {
            $this->xml_packet = file_get_contents($this->xml_filename);
        }

        if ($this->xml_packet === NULL) {
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
			return FALSE;
		}

		foreach ($this->default_params AS $key=>$value) {
			if (!isset($this->params[$key])) {
				if ($value === NULL) {
					return FALSE;
				}
				elseif ($value !== NULL) {
					$this->params[$key] = $value;
				}
			}
		}
		return TRUE;
    }

	/**
	 * Generates the xml for a standard property list
	 * @param array property list
	 * @return string
     */
    protected function generatePropertyList(array $properties)
    {
    	$result = array();

    	foreach ($properties AS $key=>$value) {
    		$xml = $this->property_template;
    		$xml = str_replace("{KEY}", $key, $xml);
    		$xml = str_replace("{VALUE}", $value, $xml);
    		$result[] = $xml;
    	}

    	return implode("\r\n", $result);
    }

    /**
	 * Generates the xml for a list of nodes
	 * @param array property list
	 * @return string
     */
    protected function generateNodeList(array $properties)
    {
    	$result = array();

		foreach ($properties AS $key=>$value) {
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
            if ($response !== FALSE) {
                $responseXml = $this->parseResponse($response);
                $this->checkResponse($responseXml);
            }
        }
        catch (ApiRequestException $e) {
            $this->error = $e;
            return FALSE;
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

        foreach($this->params AS $key=>$value) {
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
        curl_setopt($this->curl, CURLOPT_POST,           true);
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
     * @param string packet
     * @return string
     * @throws ApiRequestException
     */
    private function sendRequest($packet)
    {
        $domdoc = new \DomDocument('1.0', 'UTF-8');
        if ($domdoc->loadXml($packet) === FALSE) {
            $this->error = 'Failed to load payload';
            return FALSE;
        }

        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $domdoc->saveHTML());
        $result = curl_exec($this->curl);
        if (curl_errno($this->curl)) {
            $errmsg  = curl_error($this->curl);
            $errcode = curl_errno($this->curl);
            curl_close($this->curl);
            throw new ApiRequestException($errmsg, $errcode);
        }

        $info =  curl_getinfo($this->curl);
        $this->request_header = curl_getinfo($this->curl, CURLINFO_HEADER_OUT);

        curl_close($this->curl);
        return $result;
    }

    /**
     * Looks if API responded with correct data
     *
     * @param string response string
     * @return SimpleXMLElement
     * @throws ApiRequestException
     */
    private function parseResponse($response_string)
    {
        $xml = new \SimpleXMLElement($response_string);

        if (!is_a($xml, 'SimpleXMLElement')) {
            throw new ApiRequestException("Cannot parse server response: {$response_string}");
        }

        return $xml;
    }

    /**
     * Check data in API response
     * @return void
     * @throws ApiRequestException
     */
    private function checkResponse(\SimpleXMLElement $response) {
        if ($response->system->status == 'error') {
            throw new ApiRequestException("Error: " . $response->system->errtext);
        }
    }

    /*
	 * Helper function to search an XML tree for a specific property
	 * @return string
	 */
	protected function findProperty($node, $key, $node_name='property')
	{
		foreach ($node->children() AS $property)
		{
			if ($property->getName() == $node_name && $property->name == $key) {
				return (string)$property->value;
			}
		}
		return NULL;
	}

	/*
	 * Helper function to collapse list of xml tags into an associative array
	 * @return array
	 */
	protected function getProperties($node, $node_name='property')
	{
		$result = array();

		foreach ($node->children() AS $property)
		{
			if ($property->getName() == $node_name) {
				$result[(string)$property->name] = (string)$property->value;
			}
		}

		return $result;
    }
}
