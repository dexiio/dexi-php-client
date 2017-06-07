<?php

namespace Dexi\DTO;

class RobotDTO {

    const CRAWLER = 'CRAWLER';
    const SCRAPER = 'SCRAPER';
    const PIPES = 'PIPES';
    const AUTO = 'AUTO';

    /**
     * The ID of the robot
     * @var string
     */
    public $_id;

    /**
     * Name of the robot
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $accountId;

    /**
     * @var string
     */
    public $categoryId;

    /**
     * @var string
     */
    public $type;

    /**
     * @var int
     */
    public $editorVersion;

    /**
     * @var boolean
     */
    public $hidden = false;

    /**
     * @var object
     */
    public $proxies;

    /**
     * @var object
     */
    public $output;

    /**
     * @var string
     */
    public $outputDataType;

    /**
     * @var boolean
     */
    public $useOutputDataType;

    /**
     * @var object
     */
    public $input;

    /**
     * @var string
     */
    public $inputDataType;

    /**
     * @var boolean
     */
    public $useInputDataType;

    /**
     * @var object
     */
    public $testInput;

    /**
     * @var object
     */
    public $parentRobot;
}
