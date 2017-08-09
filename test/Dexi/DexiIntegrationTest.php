<?php

use PHPUnit\Framework\TestCase;

/**
 * Class DexiIntegrationTest
 */
class DexiIntegrationTest extends TestCase {

    private $apiKey;
    private $accountId;

    /**
     * @before
     * @throws Exception
     */
    public function beforeEach () {
        $settings = parse_ini_file('../../configuration.ini', true);
        if ($settings === false || !isset($settings['tests'])) {
            throw new Exception('Unable to run tests - no test configuration found');
        }

        $this->apiKey = $settings['tests']['apiKey'];
        $this->accountId = $settings['tests']['accountId'];

        \Dexi\Dexi::init($this->apiKey, $this->accountId);
    }

    /**
     * @test
     */
    public function canCreateARobot () {
        \Dexi\Dexi::robots()->create([
            'name' => 'Test robot'
        ]);
    }

/*$someRunId = '59f3822f-6abc-4a01-81dc-5002a31f2dbc'; // Edit your runs inside the app to get their ID

\Dexi\Dexi::init(CS_API_KEY, CS_ACCOUNT_ID);

$newExecution = \Dexi\Dexi::runs()->execute($someRunId);

var_dump($newExecution);*/
}