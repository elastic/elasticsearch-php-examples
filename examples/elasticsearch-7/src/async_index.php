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
use Elasticsearch\Common\Exceptions\ElasticsearchException;

$client = ClientBuilder::create()->build();

$stocks = [
    [
        'date'   => '2013-02-11',
        'open'   => 14.89,
        'high'   => 15.01,
        'low'    => 14.26,
        'close'  => 14.46,
        'volume' => 8882000,
        'name'   => 'AAL'
    ],
    [
        'date'   => '2013-11-21',
        'open'   => 3.43,
        'high'   => 3.44,
        'low'    => 3.35,
        'close'  => 3.37,
        'volume' => 13648029,
        'name'   => 'AMD'
    ],
    [
        'date'   => '2014-05-05',
        'open'   => 50.97,
        'high'   => 51.64,
        'low'    => 50.52,
        'close'  => 51.41,
        'volume' => 1997959,
        'name'   => 'CERN'
    ]
];


$result = [];
try {
    foreach ($stocks as $stock) {
        $result[] = $client->index([
            'index' => 'stocks',
            'body' => $stock,
            'client' => [
                'future' => 'lazy'
            ]
        ]);
    }
} catch (ElasticsearchException $e) {
    printf("Error: %s\n", $e->getMessage());
    exit(1);
}

foreach ($result as $res) {
    printf ("Document with id %s %s successfully!\n", $res['_id'], $res['result']);
}
