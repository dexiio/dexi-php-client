<?php

namespace Dexi\DTO;

class ExecutionListDTO {

    /**
     * @var int
     */
    public $offset;

    /**
     * @var int
     */
    public $totalRows;

    /**
     * @var CloudScrapeExecutionDTO[]
     */
    public $rows;
}
