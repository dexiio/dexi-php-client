<?php

namespace Dexi\DTO;

class FileDTO {

    /**
     * The type of file
     * @var string
     */
    public $mimeType;

    /**
     * The contents of the file
     * @var string
     */
    public $contents;

    function __construct($mimeType, $contents)
    {
        $this->mimeType = $mimeType;
        $this->contents = $contents;
    }
}
