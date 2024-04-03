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
            'match' => [
                'name' => 'AAL'
            ]
        ],
        'highlight' => [
            'fields' => [
                'name' => new \stdClass()
            ]
        ]
    ]
];

$result = $client->search($params);
foreach ($result['hits']['hits'] as $res) {
    print_r($res['highlight']['name']);
}
