<?php

namespace Dexi\DTO;

class RunDTO {

    const QUEUED = 'QUEUED';
    const PENDING = 'PENDING';
    const RUNNING = 'RUNNING';
    const FAILED = 'FAILED';
    const STOPPED = 'STOPPED';
    const OK = 'OK';

    /**
     * The ID of the run
     *
     * @var string
     */
    public $_id;

    /**
     * The ID of the account that owns this run
     *
     * @var string
     */
    public $accountId;

    /**
     * Name of the run
     *
     * @var string
     */
    public $name;

    /**
     * The ID of the robot that owns this run
     *
     * @var string
     */
    public $robotId;
}
