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

$client = ClientBuilder::create()->build();

$client->info();
$last = $client->transport->getLastConnection()->getLastRequestInfo();

$request = $last['request'];
$response = $last['response'];

printf ("REQUEST:\n");
printf ("%s %s\n", $request['http_method'], $request['uri']);
foreach ($request['headers'] as $name => $values) {
    printf("%s: %s\n", $name, implode(',', $values));
}
printf ("\n%s", $request['body']);

printf ("RESPONSE:\n");
printf ("%s %s\n", $response['status'], $response['reason']);
foreach ($response['headers'] as $name => $values) {
    printf("%s: %s\n", ucwords($name, '-'), implode(',', $values));
}
printf ("\n%s\n", $response['body']);
