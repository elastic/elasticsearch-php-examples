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

$start = microtime(true);

$client = ClientBuilder::create()->build();

$fileCsv = dirname(__DIR__) . '/data/all_stocks_5yr.csv';
printf ("Reading %s\n", $fileCsv);
$lines = file($fileCsv, FILE_IGNORE_NEW_LINES);
$tot = count($lines) - 1;
printf ("Indexing %d stock data\n", $tot);

$params = [
    'body' => [],
    'index' => 'stocks',
    'client' => [
        'future' => 'lazy'
    ]
];

// collects 5000 stocks per request, using the bulk API
for($i=1; $i<=$tot; $i++) {
    $params['body'][] = [
        'index' => [
            '_id' => $i
        ]
    ];
    $col = str_getcsv($lines[$i]); // read the csv row as array
    $params['body'][] = [
        'date'   => $col[0],
        'open'   => (float) $col[1],
        'high'   => (float) $col[2],
        'low'    => (float) $col[3],
        'close'  => (float) $col[4],
        'volume' => (int) $col[5],
        'name'   => $col[6]
    ];
    if ($i % 5000 === 0) {
        try {
            $responses = $client->bulk($params);
        } catch (ElasticsearchException $e) {
            printf("Error: failed to bulk data with _id from %d to %d\n", $i-5000, $i);
            printf("%s\n", $e->getMessage());
            exit(1);
        }
        printf("Indexed %d documents\n", $i);
        $lastId = $i;
        // reset the bulk request
        $params['body'] = [];
    }
}
// Send the last batch if present
if (!empty($params['body'])) {
    printf("Indexed %d documents\n", $i);
    try {
        $responses = $client->bulk($params);
    } catch (ElasticsearchException $e) {
        printf("Error: failed to bulk data with _id from %d to %d\n", $lastId, $tot);
        exit(1);
    }
}

printf("Done\n");
printf("Took %.2f sec and %d MB RAM\n", microtime(true) - $start, memory_get_usage(true) / 1048576);
