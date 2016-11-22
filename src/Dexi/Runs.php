<?php

namespace Dexi;

use Dexi\DTO\ExecutionDTO;
use Dexi\DTO\ExecutionListDTO;
use Dexi\DTO\ResultDTO;
use Dexi\DTO\RunDTO;

class Runs {

    /**
     * @var Client
     */
    private $client;

    function __construct(Client $client) {
        $this->client = $client;
    }

    /**
     * @param string $runId
     * @return RunDTO
     */
    public function get($runId) {
        return $this->client->requestJson("runs/$runId");
    }

    /**
     * Permanently delete run
     * @param string $runId
     * @return bool
     */
    public function remove($runId) {
        return $this->client->requestBoolean("runs/$runId", 'DELETE');
    }

    /**
     * Start new execution of the run
     * @param string $runId
     * @return ExecutionDTO
     */
    public function execute($runId) {
        return $this->client->requestJson("runs/$runId/execute",'POST');
    }

    /**
     * Start new execution of the run, and wait for it to finish before returning the result.
     * The execution and result will be automatically deleted from CloudScrape completion
     * - both successful and failed.
     * @param string $runId
     * @return ResultDTO
     */
    public function executeSync($runId) {
        return $this->client->requestJson("runs/$runId/execute/wait",'POST');
    }

    /**
     * Starts new execution of run with given inputs
     * @param string $runId
     * @param object $inputs
     * @return ExecutionDTO
     */
    public function executeWithInput($runId, $inputs) {
        return $this->client->requestJson("runs/$runId/execute/inputs",'POST', $inputs);
    }

    /**
     * Starts new execution of run with given inputs, and wait for it to finish before returning the result.
     * The inputs, execution and result will be automatically deleted from CloudScrape upon completion
     * - both successful and failed.
     * @param string $runId 
     *  @param array $inputs array of input objects
       * @return ExecutionDTO
       */
      public function executeBulkSync($runId, $inputs) {
          return $this->client->requestJson("runs/$runId/execute/bulk/wait",'POST', $inputs);
      }

      /**
       * Starts new execution of run with given inputs
       * @param string $runId
       * @param object $inputs
       * @return ExecutionDTO
       */
      public function executeBulk($runId, $inputs) {
          return $this->client->requestJson("runs/$runId/execute/bulk",'POST', $inputs);
      }
  
      /**
       * Starts new execution of run with given inputs, and wait for it to finish before returning the result.
       * The inputs, execution and result will be automatically deleted from CloudScrape upon completion
       * - both successful and failed.
       * @param string $runId
     * @param object|array $inputs
       * @return ExecutionDTO
     */
    public function executeWithInputSync($runId, $inputs) {
        return $this->client->requestJson("runs/$runId/execute/inputs/wait",'POST', $inputs);
    }

    /**
     * Get the result from the latest execution of the given run.
     * @param string $runId
     * @return ResultDTO
     */
    public function getLatestResult($runId) {
        return $this->client->requestJson("runs/$runId/latest/result");
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
}

