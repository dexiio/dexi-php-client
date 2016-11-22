<?php

namespace Dexi\DTO;

class ResultDTO {

    /**
     * Header fields
     * @var string[]
     */
    public $headers;

    /**
     * An array of arrays containing each row - with each value inside it.
     * @var mixed[][]
     */
    public $rows;

    /**
     * Total number of rows available
     * @var int
     */
    public $totalRows;
}
