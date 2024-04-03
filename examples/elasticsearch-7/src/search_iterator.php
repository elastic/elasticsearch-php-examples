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
use Elasticsearch\Helper\Iterators\SearchHitIterator;
use Elasticsearch\Helper\Iterators\SearchResponseIterator;

$client = ClientBuilder::create()->build();

$search_params = [
    'scroll'      => '5m',
    'index'       => '<name of index>',
    'size'        => 100,
    'body'        => [
        'query' => [
            'match_all' => new StdClass
        ]
    ]
];

$pages = new SearchResponseIterator($client, $search_params);
$hits = new SearchHitIterator($pages);

// Sample usage of iterating over hits
foreach($hits as $hit) {
    // do something with this hit. For example: write to a CSV, update a database, etc
    // e.g. prints the document id
    echo $hit['_id'], PHP_EOL;
}

// Sample usage of iterating over scrolled responses
foreach($pages as $page) {
    // do something with this hit. For example: copy its data to another index
    // e.g. prints the number of document per page (100)
    echo count($page['hits']['hits']), PHP_EOL;
}
