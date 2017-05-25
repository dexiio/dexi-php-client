<?php

namespace Dexi;

use Dexi\DTO\ExecutionDTO;
use Dexi\DTO\ExecutionListDTO;
use Dexi\DTO\ResultDTO;
use Dexi\DTO\RunDTO;
use Dexi\DTO\RunListDTO;

class Runs {
    /**
     * @var Client
     */
    private $client;

    function __construct(Client $client) {
        $this->client = $client;
    }

    /**
     * Create a run by providing its definition. If a run id (an "_id" field in the run definition) is provided and a run exists for the id,
     * the run is updated.
     *
     * @param object|array $run
     * @param string $robotId Optional. The id of the robot whose run should be updated. If not provided, the robot id of the run is used.
     * @return RunDTO
     */
    public function create($run, $robotId = null) {
        $query = ($robotId != null ? "?robotId=$robotId" : "");
        return $this->client->requestJson("runs/save$query", 'POST', $run);
    }

    /**
     * Update run by id. The run is created if not found.
     *
     * @param object|array $run
     * @param string $robotId Optional. The id of the robot whose run should be updated. If not provided, the robot id of the run is used.
     * @return RunDTO
     */
    public function update($run, $robotId = null) {
        $query = ($robotId != null ? "?robotId=$robotId" : "");
        return $this->client->requestJson("runs/save$query", 'PUT', $run);
    }

    /**
     * Get runs of the robot sorted by newest first. If no robot id is provided, all your (account's) runs are returned
     * (taking "limit" into account).
     *
     * @param string $robotId
     * @param int $offset
     * @param int $limit
     * @return RunListDTO
     */
    public function getRuns($robotId = null, $offset = 0, $limit = 30) {
        return $this->client->requestJson("runs/list?robotId=$robotId&offset=$offset&limit=$limit");

    }

    /**
     * Get run information
     *
     * @param string $runId
     * @return RunDTO
     */
    public function get($runId) {
        return $this->client->requestJson("runs/$runId");
    }

    /**
     * Permanently delete run
     *
     * @param string $runId
     * @return bool
     */
    public function remove($runId) {
        return $this->client->requestBoolean("runs/$runId", 'DELETE');
    }

    /**
     * Start new execution of the run
     *
     * @param string $runId
     * @param boolean $connect When true execution will upload its result to configured integrations for this run
     * @return ExecutionDTO
     */
    public function execute($runId, $connect = false) {
        return $this->client->requestJson("runs/$runId/execute?connect=$connect",'POST');
    }

    /**
     * Start new execution of the run, and wait for it to finish before returning the result.
     * The execution and result will be automatically deleted from CloudScrape completion
     * - both successful and failed.
     *
     * @param string $runId
     * @param boolean $connect When true execution will upload its result to configured integrations for this run
     * @param string $format Specify the format you want the output to be in. Valid values are json, csv, xml and scsv.
     * @param boolean $deleteAfter Automatically delete the execution after it succeeded, failed, both (true) or never (false). Defaults to true.
     * @return ResultDTO
     */
    public function executeSync($runId, $connect = false, $format = 'json', $deleteAfter = true) {
        $format = in_array($format, ['json', 'xml', 'csv', 'scsv']) ? $format : 'json';
        return $this->client->requestJson("runs/$runId/execute/wait?connect=$connect&format=$format&deleteAfter=$deleteAfter",'POST');
    }

    /**
     * Starts new execution of run with given inputs, and wait for it to finish before returning the result.
     * The inputs, execution and result will be automatically deleted from CloudScrape upon completion
     * - both successful and failed.
     *
     * @param string $runId
     * @param object|array $inputs
     * @param boolean $connect When true execution will upload its result to configured integrations for this run
     * @param string $format Specify the format you want the output to be in. Valid values are json, csv, xml and scsv.
     * @param boolean $deleteAfter Automatically delete the execution after it succeeded, failed, both (true) or never (false). Defaults to true.
     * @return ExecutionDTO
     */
    public function executeWithInputSync($runId, $inputs, $connect = false, $format = 'json', $deleteAfter = true) {
        $format = in_array($format, ['json', 'xml', 'csv', 'scsv']) ? $format : 'json';
        return $this->client->requestJson("runs/$runId/execute/inputs/wait?connect=$connect&format=$format&deleteAfter=$deleteAfter",'POST', $inputs);
    }

    /**
     * Starts new execution of run with given inputs, and wait for it to finish before returning the result.
     * The inputs, execution and result will be automatically deleted from CloudScrape upon completion
     * - both successful and failed.
     *
     * @param string $runId 
     * @param array $inputs array of input objects
     * @param boolean $connect When true execution will upload its result to configured integrations for this run
     * @param string $format Specify the format you want the output to be in. Valid values are json, csv, xml and scsv.
     * @param boolean $deleteAfter Automatically delete the execution after it succeeded, failed, both (true) or never (false). Defaults to true.
     * @return ExecutionDTO
     */
    public function executeBulkSync($runId, $inputs, $connect = false, $format = 'json', $deleteAfter = true) {
        $format = in_array($format, ['json', 'xml', 'csv', 'scsv']) ? $format : 'json';
        return $this->client->requestJson("runs/$runId/execute/bulk/wait?connect=$connect&format=$format&deleteAfter=$deleteAfter",'POST', $inputs);
    }

    /**
     * Starts new execution of run with given inputs
     *
     * @param string $runId
     * @param object $inputs
     * @param boolean $connect When true execution will upload its result to configured integrations for this run
     * @return ExecutionDTO
     */
    public function executeWithInput($runId, $inputs, $connect = false) {
        return $this->client->requestJson("runs/$runId/execute/inputs?connect=$connect",'POST', $inputs);
    }

    /**
     * Starts new execution of run using the input rows from the body instead of from the run itself.
     *
     * @param string $runId
     * @param object $inputs
     * @param boolean $connect When true execution will upload its result to configured integrations for this run
     * @return ExecutionDTO
     */
    public function executeBulk($runId, $inputs, $connect = false) {
        return $this->client->requestJson("runs/$runId/execute/bulk?connect=$connect",'POST', $inputs);
    }

    /**
     * Get the result from the latest execution of the given run.
     *
     * @param string $runId
     * @param string $format Specify the format you want the output to be in. Valid values are json, csv, xml and scsv.
     * @param string $state State of the execution. Valid values are null, QUEUED, PENDING, RUNNING, FAILED, STOPPED and OK
     * @return ResultDTO
     */
    public function getLatestResult($runId, $format = 'json', $state = null) {
        $format = in_array($format, ['json', 'xml', 'csv', 'scsv']) ? $format : 'json';
        $state = in_array($format, ['QUEUED', 'PENDING', 'RUNNING', 'FAILED', 'STOPPED', 'OK']) ? $state : '';
        return $this->client->requestJson("runs/$runId/latest/result?format=$format&state=$state");
    }

    /**
     * Get executions for the given run.
     *
     * @param string $runId
     * @param int $offset
     * @param int $limit
     * @return ExecutionListDTO
     */
    public function getExecutions($runId, $offset = 0, $limit = 30) {
        return $this->client->requestJson("runs/$runId/executions?offset=$offset&limit=$limit");
    }

    /**
     * Clear all inputs for run
     *
     * @param string $runId
     * @return boolean
     */
    public function clearInputs($runId) {
        return $this->client->requestBoolean("runs/$runId/inputs", 'DELETE');
    }

    /**
     * When "append" = false, set the inputs of the run to the list of inputs provided on the body (overwrite them)
     * When "append" = true, append to the existing inputs of the run the list of inputs provided on the body
     *
     * @param string $runId Primary id of the run
     * @param object|array $inputs
     * @param boolean $append Specify "set" or "append" mode
     * @param string $format Specify the format you want the output to be in. Valid values are json, csv, xml and scsv
     * @return RunDTO
     */
    public function setInputs($runId, $inputs, $append = true, $format = 'json') {
        $append = ($append ? 'true' : 'false');
        $format = (in_array($format, ['json', 'csv', 'xml', 'scsv']) ? $format : 'json');
        return $this->client->requestJson("runs/$runId/inputs?append=$append&format=$format",'POST', $inputs);
    }
}

