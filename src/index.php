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

// the document to be stored
$stock = [
    'date'   => '2013-02-08',
    'open'   => 15.07,
    'high'   => 15.12,
    'low'    => 14.63,
    'close'  => 14.75,
    'volume' => 8407500,
    'name'   => 'AAL'
];

try {
    $result = $client->index([
        'index' => 'stocks',
        'body' => $stock
    ]);
} catch (ElasticsearchException $e) {
    printf("Error: %s\n", $e->getMessage());
    exit(1);
}

printf ("Document with id %s %s successfully!\n", $result['_id'], $result['result'], $index);
