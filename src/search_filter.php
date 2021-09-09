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

$params = [
    'index' => 'stock-demo-v1',
    'body'  => [
        'query' => [
            'constant_score' => [
                'filter' => [
                    'term' => [
                        'name' => [
                            'value' => 'AAL'
                        ]
                    ]
                ]
            ]
        ]
    ]
];

$result = $client->search($params);
printf("Total docs: %d\n", $result['hits']['total']['value']);
printf("Max score : %.4f\n", $result['hits']['max_score']);
printf("Took      : %d ms\n", $result['took']);

print_r($result['hits']['hits']);
