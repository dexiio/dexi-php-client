<?php

namespace Dexi\Exception;

/**
 * Class RequestException
 *
 * @package Dexi\Exception
 */
class RequestException extends \Exception {

    private $url;
    private $response;

    /**
     * @param string $message
     * @param string $url
     * @param object $response
     */
    function __construct($message, $url, $response = null) {
        parent::__construct($message, $response ? $response->statusCode : 0);
        $this->url = $url;
        $this->response = $response;
    }

    /**
     * @return object The response object
     */
    public function getResponse() {
        return $this->response;
    }

    /**
     * The URL of the request
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }
}
