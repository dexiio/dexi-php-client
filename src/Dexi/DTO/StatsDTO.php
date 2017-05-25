<?php

namespace Dexi\DTO;

class StatsDTO {

    /**
     * Max number of concurrent running results (as configured on the run).
     * 
     * @var int
     */
    public $concurrency;


    /**
     * UTC Unix timestamp of the time the execution was created.
     * 
     * @var int
     */
    public $created;

    /**
     * The id of the user who created the execution.
     * 
     * @var string
     */
    public $createdBy;

    /**
     * The name of the user who created the execution.
     * 
     * @var string
     */
    public $createdByName;

    /**
     * UTC Unix timestamp of the time the execution is expected to finish (null for single-input executions).
     * 
     * @var int
     */
    public $eta;

    /**
     * UTC Unix timestamp of the time the execution finished (null if still active).
     * 
     * @var int
     */
    public $finished;

    /**
     * UTC Unix timestamp of the time the execution was last modified.
     * 
     * @var int
     */
    public $lastModified;

    /**
     * The id of the user who last modified the execution.
     * 
     * @var string
     */
    public $modifiedBy;

    /**
     * The name of the user who last modified the execution.
     * 
     * @var string
     */
    public $modifiedByName;

    /**
     * The number of page visits.
     * 
     * @var int
     */
    public $pageVisits;

    /**
     * The number of requests.
     * 
     * @var int
     */
    public $requests;

    /**
     * The current number of results.
     * 
     * @var int
     */
    public $resultsCurrent;

    /**
     * The number of failed results.
     * 
     * @var string
     */
    public $resultsFailed;

    /**
     * The total number of results.
     * 
     * @var string
     */
    public $resultsTotal;

    /**
     * The id of the robot.
     * 
     * @var string
     */
    public $robotId;

    /**
     * The name of the robot.
     * 
     * @var string
     */
    public $robotName;

    /**
     * The id of the run.
     * 
     * @var string
     */
    public $runId;

    /**
     * The name of the run.
     * 
     * @var string
     */
    public $runName;

    /**
     * UTC Unix timestamp of the time the execution was started.
     * 
     * @var int
     */
    public $starts;

    /**
     * The current state of the execution.
     * 
     * @var string
     */
    public $state;

    /**
     * The amount of time since the execution was started (ms).
     * 
     * @var string
     */
    public $timeTaken;

    /**
     * The amount of time the execution has run (on a worker) (ms).
     * 
     * @var int
     */
    public $timeUsed;

    /**
     * The traffic used by the execution (bytes).
     * 
     * @var int
     */
    public $trafficUsed;

}