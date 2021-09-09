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

// check if the stocks index has name as keyword mapping
$result = $client->indices()->getMapping([ 'index' => 'stocks' ]);

if ($result['stocks']['mappings']['properties']['name']['type'] !== 'keyword') {
    printf("You cannot execute the aggregation on a 'text' field. \n");
    printf("You need to re-create a `stocks` index with a custom mapping, see the %s script.\n", 'src/custom_mapping.php');
    exit(1);
}

$params = [
    'index' => 'stocks',
    'body'  => [
        "aggs" => [
            "my-agg-name" => [
                "terms" => [
                    "field" => "name",
                ]
            ]
        ]
    ]
];

$result = $client->search($params);
var_dump($result);

//print_r($result['aggregations']);
