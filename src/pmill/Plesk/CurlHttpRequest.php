<?php
namespace pmill\Plesk;

class CurlHttpRequest implements HttpRequestContract
{
    /**
     * @var resource
     */
    protected $curl;

    /**
     * @var bool
     */
    protected $last_request_result;

    /**
     * @var array
     */
    protected $last_request_header;

    /**
     * CurlHttpRequest constructor.
     * @param $host
     */
    public function __construct($host)
    {
        $this->setupCurl($host);
    }

    /**
     * @param $username
     * @param $password
     */
    public function setCredentials($username, $password)
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, [
            "HTTP_AUTH_LOGIN: ".$username,
            "HTTP_AUTH_PASSWD: ".$password,
            "HTTP_PRETTY_PRINT: TRUE",
            "Content-Type: text/xml",
        ]);
    }

    /**
     * @param $host
     */
    public function setupCurl($host)
    {
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_URL, "https://".$host.":8443/enterprise/control/agent.php");
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
    }

    /**
     * @param $body
     * @return bool|string
     * @throws ApiRequestException
     */
    public function sendRequest($body)
    {
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $body);
        $this->last_request_result = curl_exec($this->curl);

        if (curl_errno($this->curl)) {
            $errmsg  = curl_error($this->curl);
            $errcode = curl_errno($this->curl);
            curl_close($this->curl);
            throw new ApiRequestException($errmsg, $errcode);
        }

        $this->last_request_header = curl_getinfo($this->curl, CURLINFO_HEADER_OUT);
        curl_close($this->curl);

        return $this->last_request_result;
    }
}
