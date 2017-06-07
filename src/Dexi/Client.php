<?php

namespace Dexi;

use Dexi\Exception\RequestException;

class Client {

    private $endPoint = 'https://app.dexi.io/api/';
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
        $headers[] = "X-CloudScrape-Access: $this->accessKey";
        $headers[] = "X-CloudScrape-Account: $this->accountId";
        $headers[] = "User-Agent: $this->userAgent";
        $headers[] = "Accept: application/json";
        $headers[] = "Content-Type: application/json";

        if ($content) {
            $headers[] = "Content-Length: " . strlen($content);
        }

        $requestDetails = array(
            'method' => $method,
            'header' => join("\r\n",$headers),
            'content' => $content,
            'timeout' => $this->requestTimeout
        );

        $context  = stream_context_create(array(
            'https' => $requestDetails,
            'http' => $requestDetails
        ));

        $outRaw = @file_get_contents($this->endPoint . $url, false, $context);

        $out = $this->parseHeaders($http_response_header);

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

    private function parseHeaders($http_response_header) {
        $status = 0;
        $reason = '';
        $outHeaders = array();

        if ($http_response_header &&
            count($http_response_header) > 0) {
            $httpHeader = array_shift($http_response_header);
            if (preg_match('/([0-9]{3})\s+([A-Z_]+)/i', $httpHeader, $matches)) {
                $status = intval($matches[1]);
                $reason = $matches[2];
            }

            foreach($http_response_header as $header) {
                $parts = explode(':',$header,2);
                if (count($parts) < 2) {
                    continue;
                }

                $outHeaders[trim($parts[0])] = $parts[1];
            }
        }

        return (object)array(
            'statusCode' => $status,
            'reason' => $reason,
            'headers' => $outHeaders
        );
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
