![dexi.io](https://dexi.io/images/dexi-green-blue.svg "dexi.io")
# dexi-php-client
Dexi API Client for PHP 5.3+

## Installation

dexi-php-client is available via [composer/packagist](https://packagist.org/packages/dexiio/dexi-api-client) as ```dexi-api-client```. Install it by adding it to your composer.json file:

```"dexiio/dexi-api-client": "~1.0"```

or

```composer require dexiio/dexi-api-client```

## Example
The following [example](./example.php) executes a run and retrieves information for the execution:
```
<?php

// Load using the composer autoloader to handle our PSR-4 namespacing
require __DIR__ . '/vendor/autoload.php';

define('CS_API_KEY', 'Your secret API Key'); // See https://app.cloudscrape.com/#/api
define('CS_ACCOUNT_ID', 'Your account ID');
$someRunId = '59f3822f-6abc-4a01-81dc-5002a31f2dbc'; // Edit your runs inside the app to get their ID

\Dexi\Dexi::init(CS_API_KEY, CS_ACCOUNT_ID);

$newExecution = \Dexi\Dexi::runs()->execute($someRunId);

var_dump($newExecution);
```

## Documentation
See [the API documentation](https://app.dexi.com/#/api) for details on all namespaces, methods and models. The global API object must be initialized in order to be used:

```\Dexi\Dexi::init(<your api key>, <your account id>);```

The following API namespaces are contained in the global ```Dexi\Dexi``` class:

```\Dexi\Dexi::executions()```
```\Dexi\Dexi::runs()```
```\Dexi\Dexi::robots()```

These namespaces contain the methods displayed in the API documentation. Models are defined in the ```\Dexi\DTO\``` namespace.

## Migrating from cloudscrape-client-php

Github:
[cloudscrape/cloudscrape-client-php](https://github.com/cloudscrape/cloudscrape-client-php) is now **[dexiio/dexi-php-client](https://github.com/dexiio/dexi-php-client)**

Packagist: 
[cloudscrape/cloudscrape-api-client](https://packagist.org/packages/cloudscrape/cloudscrape-api-client) is now **[dexiio/dexi-api-client](https://packagist.org/packages/dexiio/dexi-api-client)**

If you are currently using cloudscrape-client-php, we strongly suggest you upgrade
to this library as cloudscrape-client-php has been deprecated and will no longer be developed, and may not be supported in the future. Most method
signatures have changed and we have added PSR-4 namespacing and rebranding to the Dexi name, as well as moving to support PHP7. New classes and methods
have also been added.

|Old class|New class|
|---------|---------|
|CloudScrape|\Dexi\Dexi|
|CloudScrapeClient|\Dexi\Client|
|CloudScrapeExecutions|\Dexi\Executions|
|CloudScrapeRuns|\Dexi\Runs|
||\Dexi\Robots|
|CloudScrapeRunDTO|\Dexi\DTO\RunDTO|
||\Dexi\DTO\RunListDTO|
|CloudScrapeResultDTO|\Dexi\DTO\ResultDTO|
|CloudScrapeFileDTO|\Dexi\DTO\FileDTO|
|CloudScrapeExecutionDTO|\Dexi\DTO\ExecutionDTO|
|CloudScrapeExecutionListDTO|\Dexi\DTO\ExecutionListDTO|
||\Dexi\DTO\StatsDTO|
||\Dexi\DTO\RobotDTO|
|CloudScrapeRequestException|\Dexi\Exception\RequestException|

## Contributing
Please submit bug reports, suggestions and pull requests to [through Github](https://github.com/dexiio/dexi-php-client/issues).

We are more than happy to examine any pull-requests and appreciate any ideas, comments or suggestions you may have.

## License
The library is available as open source under the terms of the [MIT License](http://opensource.org/licenses/MIT).