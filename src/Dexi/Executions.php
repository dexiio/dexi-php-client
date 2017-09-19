<?php

namespace Dexi;

use Dexi\DTO\ExecutionDTO;
use Dexi\DTO\FileDTO;
use Dexi\DTO\ResultDTO;
use Dexi\DTO\StatsDTO;

class Executions {

    /**
     * @var Client
     */
    private $client;

    function __construct(Client $client) {
        $this->client = $client;
    }

    /**
     * Get execution
     *
     * @param string $executionId
     * @return ExecutionDTO
     */
    public function get($executionId) {
        return $this->client->requestJson("executions/$executionId");
    }

    /**
     * Delete execution permanently
     *
     * @param string $executionId
     * @return boolean
     */
    public function remove($executionId) {
        return $this->client->requestBoolean("executions/$executionId",'DELETE');
    }

    /**
     * Get the entire result of an execution.
     *
     * @param string $executionId
     * @param string $format Specify the format you want the output to be in. Valid values are json, csv, xml and scsv.
     * @return ResultDTO
     */
    public function getResult($executionId, $format = 'json') {
        $format = in_array($format, ['json', 'xml', 'csv', 'scsv']) ? $format : 'json';
        return $this->client->requestJson("executions/$executionId/result?format=$format");
    }

    /**
     * Get a file from a result set
     *
     * @param string $executionId
     * @param string $fileId
     * @return FileDTO
     */
    public function getResultFile($executionId, $fileId) {
        $response = $this->client->request("executions/$executionId/file/$fileId");
        return new FileDTO($response->headers['content-type'], $response->content);
    }

    /**
     * Stop running execution
     *
     * @param string $executionId
     * @return boolean
     */
    public function stop($executionId) {
        return $this->client->requestBoolean("executions/$executionId/stop",'POST');
    }

    /**
     * Resume stopped execution
     *
     * @param string $executionId
     * @return boolean
     */
    public function resume($executionId) {
        return $this->client->requestBoolean("executions/$executionId/continue",'POST');
    }

    /**
     * Get statistics about an execution: basic info, usage stats and result stats.
     *
     * @param string $executionId
     * @return StatsDTO
     */
    public function getStats($executionId) {
        return $this->client->requestJson("executions/$executionId/stats");
    }
}
