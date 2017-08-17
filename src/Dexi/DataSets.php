<?php

namespace Dexi;

use Dexi\DTO\DataSetRowQueryDTO;

class DataSets {

    /**
     * @var Client
     */
    private $client;

    function __construct(Client $client) {
        $this->client = $client;
    }

    /**
     * Query the rows in your dataset - if a body is not provided, return all rows
     *
     * @param string $dataSetId
     * @param object|array|DataSetRowQueryDTO $body The query to perform
     * @return DataSetRowSetDTO
     */
    public function rows($dataSetId, $body = null) {
        return $this->client->requestJson("datasets/$dataSetId/rows", 'POST', (object) $body);
    }
}

