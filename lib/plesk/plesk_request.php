<?php

abstract class Plesk_Request
{
	protected $curl = NULL;
	protected $config = array();
	protected $params = array();

	public $xml_filename = NULL;
	public $xml_packet = NULL;
	public $request_header = NULL;
	public $error = NULL;

	abstract protected function process_response($xml);

	public function __construct($config, $params=array())
	{
		$this->config = $config;
		$this->params = $params;
		$this->init();
		if($this->xml_packet === NULL AND file_exists($this->xml_filename))
			$this->xml_packet = file_get_contents($this->xml_filename);

		if($this->xml_packet === NULL)
			throw new Exception("No XML packet supplied");
	}

	public function process()
	{
		try
		{
			$response = $this->sendRequest($this->get_packet());
		    if($response !== FALSE)
		    {
				$responseXml = $this->parseResponse($response);
				$this->checkResponse($responseXml);
			}
		}
		catch (ApiRequestException $e)
		{
			$this->error = $e;
			return FALSE;
		}
		return $this->process_response($responseXml);
	}

	protected function get_packet()
	{
		$string = $this->xml_packet;

		foreach($this->params AS $key=>$value)
			$string = str_replace('{'.strtoupper($key).'}', $value, $string);

		return $string;
	}

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
	 * @return string
	 * @throws ApiRequestException
	 */
	private function sendRequest($packet)
	{
		$domdoc = new DomDocument('1.0', 'UTF-8');
		if($domdoc->loadXml($packet) === FALSE)
		{
			$this->error = 'Failed to load payload';
			return FALSE;
		}

		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $domdoc->saveHTML());
		$result = curl_exec($this->curl);
		if (curl_errno($this->curl))
		{
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
	 * @return SimpleXMLElement
	 * @throws ApiRequestException
	 */
	private function parseResponse($response_string)
	{
		$xml = new SimpleXMLElement($response_string);
		if (!is_a($xml, 'SimpleXMLElement'))
			throw new ApiRequestException("Cannot parse server response: {$response_string}");
		return $xml;
	}

	/**
	 * Check data in API response
	 * @return void
	 * @throws ApiRequestException
	 */
	private function checkResponse(SimpleXMLElement $response)
	{
		if($response->system->status == 'error')
			throw new ApiRequestException("Error: " . $response->system->errtext);
	}
}

class ApiRequestException extends Exception {}