<?php

namespace Dexi\DTO;

class ExecutionDTO {

    const QUEUED = 'QUEUED';
    const PENDING = 'PENDING';
    const RUNNING = 'RUNNING';
    const FAILED = 'FAILED';
    const STOPPED = 'STOPPED';
    const OK = 'OK';

    /**
     * The ID of the execution
     * @var string
     */
    public $_id;

    /**
     * State of the executions. See const definitions on class to see options
     * @var string
     */
    public $_state;

    /**
     * Time the executions was started - in milliseconds since unix epoch
     * @var int
     */
    public $_starts;

    /**
     * Time the executions finished - in milliseconds since unix epoch.
     * Null if execution has not yet finished.
     * @var int
     */
    public $_finished;

}

