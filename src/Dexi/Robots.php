<?php

namespace Dexi;

use Dexi\DTO\ExecutionDTO;
use Dexi\DTO\ExecutionListDTO;
use Dexi\DTO\ResultDTO;
use Dexi\DTO\RobotDTO;
use Dexi\DTO\RunDTO;

class Robots {

    /**
     * @var Client
     */
    private $client;

    function __construct(Client $client) {
        $this->client = $client;
    }

    /**
     * Create a robot by providing its definition. To update a robot, use "update".
     *
     * @param object|array $robot
     * @return RobotDTO
     */
    public function create($robot) {
        return $this->client->requestJson("robots/save", 'POST', $robot);
    }

    /**
     * Get robot definition
     *
     * @param string $robotId
     * @return RobotDTO
     */
    public function get($robotId) {
        return $this->client->requestJson("robots/$robotId");
    }

    /**
     * Update robot definition by id. The robot is created if not found.
     *
     * @param object|array $robot
     * @return RobotDTO
     */
    public function update($robot) {
        return $this->client->requestJson("robots/save", 'POST', $robot);
    }

    /**
     * Delete robot. This will also delete all runs and executions of this robot. Deleted robots can only be recovered via the UI.
     *
     * @param string $robotId
     * @return bool
     */
    public function remove($robotId) {
        return $this->client->requestBoolean("robots/$robotId", 'DELETE');
    }
}

