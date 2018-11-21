<?php

use \PHPUnit\Framework\TestCase;
use \PHPUnit\Framework\Assert;

/**
 * Class DexiIntegrationTest
 */
class DexiIntegrationTest extends TestCase {

    private static $categoryId;
    private static $robotId;
    private static $runId;
    private static $executionId;
    private static $fileRobotId;
    private static $dataSetId;
    private static $endpoint = 'https://api.dexi.io/';

    /**
     * @before
     * @throws Exception
     */
    public function beforeEach () {
        $settings = parse_ini_file(realpath(__DIR__ . '/../../configuration.ini'), true);
        if ($settings === false || !isset($settings['tests'])) {
            throw new Exception('Unable to run tests - no test configuration found');
        }

        $apiKey = $settings['tests']['apiKey'];
        $accountId = $settings['tests']['accountId'];
        if (array_key_exists('endpoint', $settings['tests'])) {
            self::$endpoint = $settings['tests']['endpoint'];
        }

        if (array_key_exists('categoryId', $settings['tests'])) {
            self::$categoryId = $settings['tests']['categoryId'];
        }

        if (array_key_exists('dataSetId', $settings['tests'])) {
            self::$dataSetId = $settings['tests']['dataSetId'];
        }

        ;
        \Dexi\Dexi::init($apiKey, $accountId, self::$endpoint);
    }

    /**
     * @after
     */
    public function afterEach () {
        if (self::$fileRobotId) {
            try {
                \Dexi\Dexi::robots()->remove(self::$fileRobotId);
                self::$fileRobotId = null;
            } catch (Exception $exception) {
                throw new Exception('Unable to tear down file robot with ID ' . self::$fileRobotId, $exception);
            }
        }
    }

    /**
     * @test
     */
    public function Robots_create () {
        $testRobot = file_get_contents(__DIR__ . '/../resources/test.robot');
        $robotDefinition = json_decode($testRobot);
        $robotDefinition->name = 'Test robot';
        if (self::$categoryId) {
            $robotDefinition->categoryId = self::$categoryId;
        }

        $robotDTO = \Dexi\Dexi::robots()->create($robotDefinition);

        Assert::assertNotNull($robotDTO);
        Assert::assertNotNull($robotDTO->_id);
        Assert::assertEquals('Test robot', $robotDTO->name);
        Assert::assertEquals(\Dexi\Dexi::defaultClient()->getAccountId(), $robotDTO->accountId);
        Assert::assertEquals('SCRAPER', $robotDTO->type);

        self::$robotId = $robotDTO->_id;
    }

    /**
     * @test
     * @depends Robots_create
     */
    public function Robots_get () {
        $robotDTO = \Dexi\Dexi::robots()->get(self::$robotId);

        Assert::assertNotNull($robotDTO);
        Assert::assertNotNull($robotDTO->_id);
        Assert::assertEquals('Test robot', $robotDTO->name);
        Assert::assertEquals(\Dexi\Dexi::defaultClient()->getAccountId(), $robotDTO->accountId);
        Assert::assertEquals('SCRAPER', $robotDTO->type);
    }

    /**
     * @test
     * @depends Robots_create
     */
    public function Robots_update () {
        $testRobot = file_get_contents(__DIR__ . '/../resources/test.robot');
        $robotDefinition = json_decode($testRobot);
        $robotDefinition->_id = self::$robotId;
        $robotDefinition->name = 'Edited test robot';
        if (self::$categoryId) {
            $robotDefinition->categoryId = self::$categoryId;
        }

        $robotDTO = \Dexi\Dexi::robots()->update($robotDefinition);

        Assert::assertNotNull($robotDTO);
        Assert::assertNotNull($robotDTO->_id);
        Assert::assertEquals(self::$robotId, $robotDTO->_id);
        Assert::assertEquals('Edited test robot', $robotDTO->name);
        Assert::assertEquals(\Dexi\Dexi::defaultClient()->getAccountId(), $robotDTO->accountId);
        Assert::assertEquals('SCRAPER', $robotDTO->type);
    }

    /**
     * @test
     * @depends Robots_create
     */
    public function Runs_create () {
        $runDTO = \Dexi\Dexi::runs()->create([
            'name' => 'Test robot run',
            'robotId' => self::$robotId
        ]);

        Assert::assertNotNull($runDTO);
        Assert::assertNotNull($runDTO->_id);
        Assert::assertEquals('Test robot run', $runDTO->name);
        Assert::assertEquals(\Dexi\Dexi::defaultClient()->getAccountId(), $runDTO->accountId);
        Assert::assertEquals(self::$robotId, $runDTO->robotId);

        self::$runId = $runDTO->_id;
    }

    /**
     * @test
     * @depends Runs_create
     * @group Runs
     */
    public function Runs_get () {
        $runDTO = \Dexi\Dexi::runs()->get(self::$runId);

        Assert::assertNotNull($runDTO);
        Assert::assertNotNull($runDTO->_id);
        Assert::assertEquals(self::$runId, $runDTO->_id);
        Assert::assertEquals('Test robot run', $runDTO->name);
        Assert::assertEquals(\Dexi\Dexi::defaultClient()->getAccountId(), $runDTO->accountId);
        Assert::assertEquals(self::$robotId, $runDTO->robotId);
    }

    /**
     * @test
     * @depends Runs_create
     * @group Runs
     */
    public function Runs_update () {
        $runDTO = \Dexi\Dexi::runs()->update([
            '_id' => self::$runId,
            'name' => 'Edited test robot run',
            'robotId' => self::$robotId,
            'accountId' => \Dexi\Dexi::defaultClient()->getAccountId(),
            'version' => 1
        ]);

        Assert::assertNotNull($runDTO);
        Assert::assertNotNull($runDTO->_id);
        Assert::assertEquals(self::$runId, $runDTO->_id);
        Assert::assertEquals('Edited test robot run', $runDTO->name);
        Assert::assertEquals(\Dexi\Dexi::defaultClient()->getAccountId(), $runDTO->accountId);
        Assert::assertEquals(self::$robotId, $runDTO->robotId);
    }

    /**
     * @test
     * @depends Runs_create
     * @group Runs
     */
    public function Runs_setInputs () {
        $runDTO = \Dexi\Dexi::runs()->setInputs(self::$runId, [
            ['input_1' => 'First input set'],
            ['input_1' => 'Second input set']
        ]);

        Assert::assertNotNull($runDTO);
    }

    /**
     * @test
     * @depends Runs_create
     * @group Runs
     */
    public function Runs_getRuns () {
        $runListDTO = \Dexi\Dexi::runs()->getRuns(self::$robotId);

        Assert::assertNotNull($runListDTO);
        Assert::assertTrue(isset($runListDTO->offset));
        Assert::assertTrue(isset($runListDTO->totalRows));
        Assert::assertTrue(isset($runListDTO->rows));

        Assert::assertEquals(0, $runListDTO->offset);
        Assert::assertEquals(1, $runListDTO->totalRows);
        Assert::assertEquals(1, count($runListDTO->rows));

        $runDTO = $runListDTO->rows[0];

        Assert::assertNotNull($runDTO);
        Assert::assertNotNull($runDTO->_id);
        Assert::assertEquals(self::$runId, $runDTO->_id);
        Assert::assertEquals('Edited test robot run', $runDTO->name);
        Assert::assertEquals(self::$robotId, $runDTO->robotId);
    }

    /**
     * @test
     * @depends Runs_create
     * @group Runs
     */
    public function Runs_execute () {
        $executionDTO = \Dexi\Dexi::runs()->execute(self::$runId, false);

        Assert::assertNotNull($executionDTO);
        Assert::assertNotNull($executionDTO->state);
        Assert::assertEquals(self::$robotId, $executionDTO->robotId);
        Assert::assertEquals(self::$runId, $executionDTO->runId);

        self::$executionId = $executionDTO->_id;
    }

    /**
     * @test
     * @depends Runs_execute
     * @group Executions
     */
    public function Executions_get () {
        $executionDTO = \Dexi\Dexi::executions()->get(self::$executionId);

        Assert::assertNotNull($executionDTO);
        Assert::assertNotNull($executionDTO->state);
        Assert::assertEquals(self::$robotId, $executionDTO->robotId);
        Assert::assertEquals(self::$runId, $executionDTO->runId);
    }

    /**
     * @test
     * @depends Runs_execute
     * @group Executions
     */
    public function Executions_stop () {
        Assert::assertNotNull(\Dexi\Dexi::executions()->stop(self::$executionId));
    }

    /**
     * @test
     * @depends Runs_execute
     * @group Executions
     */
    public function Executions_getStats () {
        $stats = \Dexi\Dexi::executions()->getStats(self::$executionId);

        Assert::assertNotNull($stats->created);
        Assert::assertNotNull($stats->createdBy);
        Assert::assertNotNull($stats->createdByName);
        Assert::assertNotNull($stats->lastModified);
        Assert::assertNotNull($stats->modifiedBy);
        Assert::assertNotNull($stats->modifiedByName);
        Assert::assertNotNull($stats->state);
        Assert::assertNotNull($stats->created);

        Assert::assertInternalType('int', $stats->pageVisits);
        Assert::assertInternalType('int', $stats->requests);
        Assert::assertInternalType('int', $stats->timeUsed);
        Assert::assertInternalType('int', $stats->trafficUsed);
        Assert::assertInternalType('int', $stats->resultsCurrent);
        Assert::assertInternalType('int', $stats->resultsFailed);
        Assert::assertInternalType('int', $stats->resultsTotal);

        Assert::assertFalse($stats->archived);

        Assert::assertEquals(self::$robotId, $stats->robotId);
        Assert::assertEquals(self::$runId, $stats->runId);
        Assert::assertEquals('Edited test robot', $stats->robotName);
        Assert::assertEquals('Edited test robot run', $stats->runName);
        Assert::assertInternalType('int', $stats->timeTaken);
        Assert::assertTrue($stats->timeTaken > 0);
    }

    /**
     * @test
     * @depends Runs_execute
     * @group Runs
     */
    public function Runs_getExecutions () {
        $executionListDTO = \Dexi\Dexi::runs()->getExecutions(self::$runId);

        Assert::assertNotNull($executionListDTO);
        Assert::assertNotNull($executionListDTO->rows);
        Assert::assertEquals(1, $executionListDTO->totalRows);
        Assert::assertEquals(1, count($executionListDTO->rows));

        $executionDTO = $executionListDTO->rows[0];

        Assert::assertNotNull($executionDTO);
        Assert::assertNotNull($executionDTO->state);
        Assert::assertEquals(self::$robotId, $executionDTO->robotId);
        Assert::assertEquals(self::$runId, $executionDTO->runId);
    }

    /**
     * @test
     * @depends Runs_create
     * @group Runs
     */
    public function Runs_executeSync () {
        $resultDTO = \Dexi\Dexi::runs()->executeSync(self::$runId, false, 'json', true);

        Assert::assertNotNull($resultDTO);
        Assert::assertEquals(2, $resultDTO->totalRows);

        Assert::assertNotNull($resultDTO->headers);
        Assert::assertEquals(3, count($resultDTO->headers));
        Assert::assertEquals(2, count($resultDTO->rows));

        Assert::assertEquals(['input_1', 'output_1', 'error'], $resultDTO->headers);

        Assert::assertTrue('First input set' == $resultDTO->rows[0][0] ||'Second input set' == $resultDTO->rows[0][0]);
        Assert::assertTrue('First input set' == $resultDTO->rows[0][1] ||'Second input set' == $resultDTO->rows[0][1]);
        Assert::assertNull($resultDTO->rows[0][2]);

        Assert::assertTrue('First input set' == $resultDTO->rows[1][0] ||'Second input set' == $resultDTO->rows[1][0]);
        Assert::assertTrue('First input set' == $resultDTO->rows[1][1] ||'Second input set' == $resultDTO->rows[1][1]);
        Assert::assertNull($resultDTO->rows[1][2]);
    }

    /**
     * @test
     * @depends Runs_create
     * @group Runs
     */
    public function Runs_executeWithInputSync () {
        $inputs = ['input_1' => 'First alternative input set'];

        $resultDTO = \Dexi\Dexi::runs()->executeWithInputSync(self::$runId, $inputs, false, 'json', true);

        Assert::assertNotNull($resultDTO);
        Assert::assertEquals(1, $resultDTO->totalRows);

        Assert::assertNotNull($resultDTO->headers);
        Assert::assertEquals(3, count($resultDTO->headers));
        Assert::assertEquals(1, count($resultDTO->rows));

        Assert::assertEquals(['input_1', 'output_1', 'error'], $resultDTO->headers);

        Assert::assertEquals(['First alternative input set', 'First alternative input set', null], $resultDTO->rows[0]);
    }

    /**
     * @test
     * @depends Runs_create
     * @group Runs
     */
    public function Runs_executeBulkSync () {
        $inputs = [
            ['input_1' => 'First alternative input set'],
            ['input_1' => 'Second alternative input set']
        ];

        $resultDTO = \Dexi\Dexi::runs()->executeBulkSync(self::$runId, $inputs, false, 'json', true);

        Assert::assertNotNull($resultDTO);
        Assert::assertEquals(2, $resultDTO->totalRows);

        Assert::assertNotNull($resultDTO->headers);
        Assert::assertEquals(3, count($resultDTO->headers));
        Assert::assertEquals(2, count($resultDTO->rows));

        Assert::assertEquals(['input_1', 'output_1', 'error'], $resultDTO->headers);

        Assert::assertTrue('First alternative input set' == $resultDTO->rows[0][0] ||'Second alternative input set' == $resultDTO->rows[0][0]);
        Assert::assertTrue('First alternative input set' == $resultDTO->rows[0][1] ||'Second alternative input set' == $resultDTO->rows[0][1]);
        Assert::assertNull($resultDTO->rows[0][2]);

        Assert::assertTrue('First alternative input set' == $resultDTO->rows[1][0] ||'Second alternative input set' == $resultDTO->rows[1][0]);
        Assert::assertTrue('First alternative input set' == $resultDTO->rows[1][1] ||'Second alternative input set' == $resultDTO->rows[1][1]);
        Assert::assertNull($resultDTO->rows[1][2]);
    }

    /**
     * @test
     * @depends Runs_create
     * @group Runs
     */
    public function Runs_executeWithInput () {
        $inputs = ['input_1' => 'First alternative input set'];

        $executionDTO = \Dexi\Dexi::runs()->executeWithInput(self::$runId, $inputs, false);

        Assert::assertNotNull($executionDTO);
        Assert::assertNotNull($executionDTO->state);
        Assert::assertEquals(self::$robotId, $executionDTO->robotId);
        Assert::assertEquals(self::$runId, $executionDTO->runId);
    }

    /**
     * @test
     * @depends Runs_create
     * @group Runs
     */
    public function Runs_executeBulk () {
        $inputs = [
            ['input_1' => 'First alternative input set'],
            ['input_1' => 'Second alternative input set']
        ];

        $executionDTO = \Dexi\Dexi::runs()->executeBulk(self::$runId, $inputs, false);

        Assert::assertNotNull($executionDTO);
        Assert::assertNotNull($executionDTO->state);
        Assert::assertEquals(self::$robotId, $executionDTO->robotId);
        Assert::assertEquals(self::$runId, $executionDTO->runId);
    }

    /**
     * @test
     * @depends Runs_execute
     * @group Runs
     */
    public function Executions_getResult () {

        $timeout = 120;
        $startTime = time();

        while (true) {
            $executionDTO = \Dexi\Dexi::executions()->get(self::$executionId);

            if ($executionDTO->finished || time() > $startTime + $timeout) {
                break;
            }

            sleep(5);
        }

        $resultDTO = \Dexi\Dexi::executions()->getResult(self::$executionId);

        Assert::assertNotNull($resultDTO);
        Assert::assertEquals(1, $resultDTO->totalRows);

        Assert::assertNotNull($resultDTO->headers);
        Assert::assertEquals(3, count($resultDTO->headers));
        Assert::assertEquals(1, count($resultDTO->rows));

        Assert::assertEquals(['input_1', 'output_1', 'error'], $resultDTO->headers);

        // At this point the results may not be ready but at least we can check that the input is there
        Assert::assertEquals('First input set', $resultDTO->rows[0][0]);
    }

    /**
     * @test
     * @depends Runs_execute
     * @group Runs
     */
    public function Runs_getLatestResult () {
        $resultDTO = \Dexi\Dexi::runs()->getLatestResult(self::$runId);

        Assert::assertNotNull($resultDTO);
        Assert::assertNotNull($resultDTO->totalRows);
        Assert::assertInternalType('int', $resultDTO->totalRows);
    }

    /**
     * @test
     * @depends Runs_setInputs
     * @group Runs
     */
    public function Runs_clearInputs () {
        Assert::assertNotNull(\Dexi\Dexi::runs()->clearInputs(self::$runId));
    }

    /**
     * @test
     * @depends Runs_execute
     * @group Runs
     */
    public function Executions_remove () {
        $response = \Dexi\Dexi::executions()->remove(self::$executionId);

        Assert::assertTrue($response);

        self::$executionId = null;
    }

    /**
     * @test
     * @depends Robots_create
     * @depends Runs_execute
     * @depends Executions_get
     * @depends Executions_getResult
     * @group Executions
     */
    public function Executions_getResultFile () {
        $testRobot = file_get_contents(__DIR__ . '/../resources/test-file.robot');
        $robotDefinition = json_decode($testRobot);
        $robotDefinition->name = 'Test file robot';
        if (self::$categoryId) {
            $robotDefinition->categoryId = self::$categoryId;
        }

        $robotDTO = \Dexi\Dexi::robots()->create($robotDefinition);
        Assert::assertNotNull($robotDTO);
        Assert::assertNotNull($robotDTO->_id);

        self::$fileRobotId = $robotDTO->_id;

        $runDTO = \Dexi\Dexi::runs()->create([
            'name' => 'Test file robot run',
            'robotId' => $robotDTO->_id
        ]);

        Assert::assertNotNull($runDTO);
        Assert::assertNotNull($runDTO->_id);

        $executionDTO = \Dexi\Dexi::runs()->execute($runDTO->_id, false);

        $timeout = 120;
        $startTime = time();

        while (true) {
            $executionDTO = \Dexi\Dexi::executions()->get($executionDTO->_id);

            if ($executionDTO->finished) {
                break;
            } else if (time() > $startTime + $timeout) {
                throw new Exception('Timed out waiting for response from execution');
            }

            sleep(5);
        }

        $resultDTO = \Dexi\Dexi::executions()->getResult($executionDTO->_id);

        Assert::assertNotNull($resultDTO);
        Assert::assertNotNull($resultDTO->headers);
        Assert::assertNotNull($resultDTO->rows);
        Assert::assertEquals(['image', 'error'], $resultDTO->headers);
        Assert::assertEquals(1, $resultDTO->totalRows);
        Assert::assertEquals(1, count($resultDTO->rows));
        Assert::assertNotNull($resultDTO->rows[0][0]);
        Assert::assertNull($resultDTO->rows[0][1]);

        $fileDefinition = $resultDTO->rows[0][0];
        Assert::assertStringStartsWith('FILE:image/png;', $fileDefinition);

        $fileIdParts = explode(';', $fileDefinition);
        $fileId = array_pop($fileIdParts);
        $fileDTO = \Dexi\Dexi::executions()->getResultFile($executionDTO->_id, $fileId);

        Assert::assertNotNull($fileDTO);
        Assert::assertNotNull($fileDTO->mimeType);
        Assert::assertNotNull($fileDTO->contents);
        Assert::assertEquals('image/png', $fileDTO->mimeType);
        Assert::assertTrue(strlen($fileDTO->contents) > 0);
    }

    /**
     * @test
     * @depends Runs_create
     * @group Runs
     */
    public function Runs_remove () {
        $response = \Dexi\Dexi::runs()->remove(self::$runId);

        Assert::assertTrue($response);
    }

    /**
     * @test
     * @depends Robots_create
     * @group Robots
     */
    public function Robots_remove () {
        $response = \Dexi\Dexi::robots()->remove(self::$robotId);

        Assert::assertTrue($response);
    }

    /**
     * @test
     * @group DataSets
     */
    public function DataSets_rows () {
        if (!self::$dataSetId) {
            throw new \PHPUnit\Framework\Warning('Set a tests.dataSetId variable in the configuration to be able to perform data sets tests');
        }

        $dataSetRowSetDTO = \Dexi\Dexi::dataSets()->rows(self::$dataSetId);

        Assert::assertNotNull($dataSetRowSetDTO->rows);
        Assert::assertNotNull($dataSetRowSetDTO->offset);
        Assert::assertNotNull($dataSetRowSetDTO->totalRows);

        Assert::assertInternalType('int', $dataSetRowSetDTO->offset);
        Assert::assertInternalType('int', $dataSetRowSetDTO->totalRows);
        Assert::assertInternalType('array', $dataSetRowSetDTO->rows);
    }
}