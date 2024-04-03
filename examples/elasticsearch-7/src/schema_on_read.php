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

$result = $client->search([
    'index' => 'stocks',
    'body' => [
        'runtime_mappings' => [
            'average' => [
                'type' => 'double',
                'script' => [
                    'source' => "emit((double)(doc['high'].value + doc['low'].value)/2)"
                ]
            ]
        ],
        'fields' => [
            'average'
        ]
    ]
]);

printf("We have a new 'average' field in the result (ie. the average of high and low stock values).\n");
print_r($result);