<?php

namespace Dexi\DTO;


class DataSetRowSetDTO {

    /**
     * @var int
     */
    public $offset;

    /**
     * @var int
     */
    public $totalRows;

    /**
     * @var object[]
     */
    public $rows;
}