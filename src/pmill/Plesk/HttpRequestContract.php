<?php
namespace pmill\Plesk;

interface HttpRequestContract
{
    /**
     * CurlHttpRequest constructor.
     * @param $host
     */
    public function __construct($host);

    /**
     * @param $username
     * @param $password
     */
    public function setCredentials($username, $password);

    /**
     * @param $body
     * @return bool|string
     * @throws ApiRequestException
     */
    public function sendRequest($body);
}