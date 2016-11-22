<?php

namespace Dexi\Exception;

class RequestException extends \Exception {

    private $response;
    private $url;

    /**
     * @param string $msg
     * @param string $url
     * @param object $response
     */
    function __construct($msg, $url, $response) {
        parent::__construct($msg, $response->statusCode);
        $this->response = $response;
        $this->url = $url;
    }

    /**
     * @return object The response object
     */
    public function getResponse()
    {
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
