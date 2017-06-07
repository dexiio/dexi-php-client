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
     * @var string
     */
    public $_id;

    /**
     * Name of the run
     * @var string
     */
    public $name;
}
