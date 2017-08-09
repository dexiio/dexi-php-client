<?php

namespace Dexi;

use Dexi\Exception\RequestException;

class Client {

    private $endPoint = 'https://api.dexi.io/';
    private $userAgent = 'CS-PHP-CLIENT/1.0';
    private $apiKey;
    private $accountId;
    private $accessKey;

    private $requestTimeout = 3600;

    /**
     * @var Executions
     */
    private $executions;

    /**
     * @var Runs
     */
    private $runs;

    function __construct($apiKey, $accountId) {
        $this->apiKey = $apiKey;
        $this->accountId = $accountId;
        $this->accessKey = md5($accountId . $apiKey);

        $this->executions = new Executions($this);
        $this->runs = new Runs($this);
        $this->robots = new Robots($this);
    }

    /**
     * Get current request timeout
     * @return int
     */
    public function getRequestTimeout() {
        return $this->requestTimeout;
    }

    /**
     * Set request timeout. Defaults to 1 hour.
     *
     * Note: If you are using the sync methods and some requests are running for very long you need to increase this value.
     *
     * @param int $requestTimeout
     */
    public function setRequestTimeout($requestTimeout) {
        $this->requestTimeout = $requestTimeout;
    }



    /**
     * Get endpoint / base url of requests
     * @return string
     */
    public function getEndPoint() {
        return $this->endPoint;
    }

    /**
     * Set end point / base url of requests
     * @param string $endPoint
     */
    public function setEndPoint($endPoint) {
        $this->endPoint = $endPoint;
    }

    /**
     * Get user agent of requests
     * @return string
     */
    public function getUserAgent() {
        return $this->userAgent;
    }

    /**
     * Set user agent of requests
     * @param string $userAgent
     */
    public function setUserAgent($userAgent) {
        $this->userAgent = $userAgent;
    }


    /**
     *
     * Make a call to the CloudScrape API
     * @param string $url
     * @param string $method
     * @param mixed $body Will be converted into json
     * @return object
     * @throws RequestException
     */
    public function request($url, $method = 'GET', $body = null) {
        $content = $body ? json_encode($body) : null;

        $headers = array();
        $headers[] = "X-DexiIO-Access: $this->accessKey";
        $headers[] = "X-DexiIO-Account: $this->accountId";
        $headers[] = "User-Agent: $this->userAgent";
        $headers[] = "Accept: application/json";
        $headers[] = "Content-Type: application/json";

        if ($content) {
            $headers[] = "Content-Length: " . strlen($content);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$this->endPoint . $url);
        curl_setopt($ch, CURLOPT_POST, $method == 'POST');
        curl_setopt($ch,CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch,CURLOPT_TIMEOUT,$this->requestTimeout);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        $response = curl_exec($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $headerSize);
        $outRaw = substr($response, $headerSize);


        $out = (object)array(
            'statusCode' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
            'reason' => curl_error($ch),
            'headers' => $header,
        );
        curl_close($ch);

        $out->content = $outRaw;

        if ($out->statusCode < 100 || $out->statusCode > 399) {
            throw new RequestException("CloudScrape request failed: $out->statusCode $out->reason", $url, $out);
        }

        return $out;
    }

    /**
     * @param string $url
     * @param string $method
     * @param mixed $body
     * @return mixed
     * @throws RequestException
     */
    public function requestJson($url, $method = 'GET', $body = null) {
        $response = $this->request($url, $method, $body);
        return json_decode($response->content);
    }

    /**
     * @param string $url
     * @param string $method
     * @param mixed $body
     * @return bool
     * @throws RequestException
     */
    public function requestBoolean($url, $method = 'GET', $body = null) {
        $this->request($url, $method, $body);
        return true;
    }

    /**
     * Interact with executions.
     * @return Executions
     */
    public function executions() {
        return $this->executions;
    }

    /**
     * Interact with runs
     * @return Runs
     */
    public function runs() {
        return $this->runs;
    }

    /**
     * Interact with robots
     * @return Robots
     */
    public function robots() {
        return $this->robots;
    }
}
