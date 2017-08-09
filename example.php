<?php

require __DIR__ . '/vendor/autoload.php';

//See https://app.dexi.io/#/api
define('CS_API_KEY', 'Your secret API Key'); // See https://app.dexi.io/#/api
define('CS_ACCOUNT_ID', 'Your account ID');
$someRunId = 'The ID for a run'; // Edit your runs inside the app to get their ID

\Dexi\Dexi::init(CS_API_KEY, CS_ACCOUNT_ID);

$newExecution = \Dexi\Dexi::runs()->execute($someRunId);

var_dump($newExecution);