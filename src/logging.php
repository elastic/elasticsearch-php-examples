<?php
/**
 * Elasticsearch PHP examples
 *
 * @link      https://github.com/elastic/elasticsearch-php-examples
 * @copyright Copyright (c) Elasticsearch B.V (https://www.elastic.co)
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 *
 * Licensed to Elasticsearch B.V under one or more agreements.
 * Elasticsearch B.V licenses this file to you under the Apache 2.0 License.
 * See the LICENSE file in the project root for more information.
 */
require dirname(__DIR__) .  '/vendor/autoload.php';

use Elasticsearch\ClientBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$logFile = dirname(__DIR__) . '/log/elasticsearch-php.log';
$logger = new Logger('name');
$logger->pushHandler(new StreamHandler($logFile, Logger::DEBUG));

$client = ClientBuilder::create()
    ->setLogger($logger)
    ->build();   

$result = $client->info();

printf("You can read the log in %s file\n", $logFile);
