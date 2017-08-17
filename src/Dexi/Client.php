<?php

namespace Dexi;

use Dexi\Exception\RequestException;

class Client {

    /**
     * @var string
     */
    private $endPoint = 'https://api.dexi.io/';

    /**
     * @var string
     */
    private $userAgent = 'CS-PHP-CLIENT/1.0';

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $accountId;

    /**
     * @var string
     */
    private $accessKey;

    /**
     * @var int
     */
    private $requestTimeout = 3600;

    /**
     * @var Executions
     */
    private $executions;

    /**
     * @var Runs
     */
    private $runs;

    /**
     * @var Robots
     */
    private $robots;

    /**
     * @var DataSets
     */
    private $dataSets;

    /**
     * Client constructor
     *
     * @param string $apiKey
     * @param string $accountId
     */
    function __construct($apiKey, $accountId) {
        $this->apiKey = $apiKey;
        $this->accountId = $accountId;
        $this->accessKey = md5($accountId . $apiKey);

        $this->executions = new Executions($this);
        $this->runs = new Runs($this);
        $this->robots = new Robots($this);
        $this->dataSets = new DataSets($this);
    }

    /**
     * @return string
     */
    public function getAccountId() {
        return $this->accountId;
    }

    /**
     * Get current request timeout
     *
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
     *
     * @return string
     */
    public function getEndPoint() {
        return $this->endPoint;
    }

    /**
     * Set end point / base url of requests
     *
     * @param string $endPoint
     */
    public function setEndPoint($endPoint) {
        $this->endPoint = $endPoint;
    }

    /**
     * Get user agent of requests
     *
     * @return string
     */
    public function getUserAgent() {
        return $this->userAgent;
    }

    /**
     * Set user agent of requests
     *
     * @param string $userAgent
     */
    public function setUserAgent($userAgent) {
        $this->userAgent = $userAgent;
    }


    /**
     * Make a call to the Dexi API
     *
     * @param string $url
     * @param string $method
     * @param mixed $body Will be converted into json
     * @return object
     * @throws RequestException
     */
    public function request($url, $method = 'GET', $body = null) {
        $content = $body ? json_encode($body) : null;

        $headers = [];
        $headers[] = "X-DexiIO-Access: $this->accessKey";
        $headers[] = "X-DexiIO-Account: $this->accountId";
        $headers[] = "User-Agent: $this->userAgent";
        $headers[] = "Accept: application/json";
        $headers[] = "Content-Type: application/json";

        if ($content) {
            $headers[] = "Content-Length: " . strlen($content);
        }

        $fullUrl = $this->endPoint . $url;
        $out = $this->executeCurlRequest($fullUrl, $headers, $content, $method);

        if ($out->statusCode < 100 || $out->statusCode > 399) {
            $payload = json_decode($out->content);
            if ($payload !== null) {
                if (isset($payload->msg)) {
                    throw new RequestException("Dexi request failed: $out->statusCode $payload->msg", $url, $out);
                } else if (isset($payload->message)) {
                    throw new RequestException("Dexi request failed: $out->statusCode $payload->message", $url, $out);
                }
            }

            throw new RequestException("Dexi request failed: $out->statusCode $out->reason", $url, $out);
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
     * Interact with executions
     *
     * @return Executions
     */
    public function executions() {
        return $this->executions;
    }

    /**
     * Interact with runs
     *
     * @return Runs
     */
    public function runs() {
        return $this->runs;
    }

    /**
     * Interact with robots
     *
     * @return Robots
     */
    public function robots() {
        return $this->robots;
    }

    /**
     * Interact with data sets
     *
     * @return DataSets
     */
    public function dataSets() {
        return $this->dataSets;
    }

    /**
     * @param string $url
     * @param string[] $headers
     * @param string $body
     * @param string $method
     * @return mixed
     */
    private function executeCurlRequest($url, $headers, $body = '', $method = 'GET') {
        $ch = curl_init($url);

        switch (strtoupper($method)) {
            case 'POST':
            case 'PUT':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
                break;
        }

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->requestTimeout);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headerDefinition = $this->parseHeaderDefinition(substr($response, 0, $headerSize));
        $out = (object) [
            'statusCode' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
            'reason' => $headerDefinition->reason,
            'headers' => $headerDefinition->headers,
            'content' => substr($response, $headerSize)
        ];

        curl_close($ch);

        return $out;
    }

    /**
     * Parse a header definition to retrieve the HTTP status code, the text description and the actual headers
     *
     * @param string|string[] $headerDefinition
     * @return object
     */
    private function parseHeaderDefinition ($headerDefinition) {
        $status = 0;
        $reason = '';

        if (is_array($headerDefinition)) {
            $rawHeaders = $headerDefinition;
        } else {
            $rawHeaders = explode("\r\n", $headerDefinition);
        }

        $headers = [];
        if ($rawHeaders && count($rawHeaders) > 0) {
            $httpHeader = array_shift($rawHeaders);
            if (preg_match('/([0-9]{3})\s+([A-Z_]+)/i', $httpHeader, $matches)) {
                $status = intval($matches[1]);
                $reason = $matches[2];
            }

            foreach($rawHeaders as $header) {
                $parts = explode(':', $header,2);
                if (count($parts) < 2) {
                    continue;
                }

                $headers[trim($parts[0])] = trim($parts[1]);
            }
        }
        return (object) [
            'statusCode' => $status,
            'reason' => $reason,
            'headers' => $headers
        ];
    }
}
