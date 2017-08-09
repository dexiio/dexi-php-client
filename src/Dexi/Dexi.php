<?php

namespace Dexi;

class Dexi {

    /**
     * @var Client
     */
    private static $client;

    /**
     * @param $apiKey
     * @param $accountId
     */
    public static function init($apiKey, $accountId) {
        self::$client = new Client($apiKey, $accountId);
    }

    /**
     * @return Client
     * @throws \Exception if Dexi::init was not called
     */
    public static function defaultClient() {
        self::checkState();

        return self::$client;
    }

    /**
     * @return Executions
     * @throws \Exception if Dexi::init was not called
     */
    public static function executions() {
        self::checkState();

        return self::$client->executions();
    }

    /**
     * @return Runs
     * @throws \Exception if Dexi::init was not called
     */
    public static function runs() {
        self::checkState();

        return self::$client->runs();
    }

    /**
     * @return Robots
     * @throws \Exception if Dexi::init was not called
     */
    public static function robots() {
        self::checkState();

        return self::$client->robots();
    }

    /**
     * @throws \Exception
     */
    private static function checkState() {
        if (!self::$client) {
            throw new \Exception('You must call init first before using the API');
        }
    }
}
