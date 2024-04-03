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
require __DIR__ . '/vendor/autoload.php';

use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\ElasticsearchException;

$start = microtime(true);

$client = ClientBuilder::create()
    ->build();

// Create the books index with custom mapping
$client->indices()->create([
    'index' => 'books',
    'body' => [
        'mappings' => [
            'properties' => [
                'title' => [
                    'type' => 'text'
                ],
                'description' => [
                    'type' => 'text'
                ],
                'author' => [
                    'type' => 'text'
                ],
                'year' => [
                    'type' => 'short'
                ],
                'publisher' => [
                    'type' => 'keyword'
                ],
                'rating' => [
                    'type' => 'half_float'
                ]
            ]
        ]
    ]
]);


// Import the CSV file to Elasticsearch

$size = 5000; // size of the bulk ingestion
$csv = fopen(__DIR__. "/data/books.csv", "r");
$first = fgetcsv($csv, 8192, ";");

$params = [
    'index' => 'books',
    'body' => []
];
$count = 1;
while (($data = fgetcsv($csv, 8192, ";")) !== false) {
    if (count($data) !== 6) {
        var_dump($data);
        die;
    }
    $params['body'][] = [
        'index' => [
            '_id' => $count
        ]
    ];
    $params['body'][] = [
        'title'       => $data[0],
        'description' => $data[1],
        'author'      => $data[2],
        'year'        => (int) $data[3],
        'publisher'   => $data[4],
        'rating'      => (float) $data[5]
    ];
    if ($count % $size === 0) {
        try {
            $response = $client->bulk($params);
            if ($response['errors']) {
                $i = 1;
                foreach ($response['items'] as $item) {
                    if (isset($item['index']['error'])) {
                        printf("ERROR #%d\n", $i);
                        printf("Status code: %s\n", $item['index']['status']);
                        printf("Type: %s\n", $item['index']['error']['type']);
                        printf("Reason: %s\n", $item['index']['error']['reason']);
                        $i++;
                    }
                }
                die();
            }
        } catch (ElasticsearchException $e) {
            printf("Error: failed to bulk data with _id from %d to %d\n", $count - $size, $count);
            printf("%s\n", $e->getMessage());
            exit(1);
        }
        printf("Indexed %d documents\n", $count);
        $params['body'] = [];
        $lastId = $count;
    }
    $count++;
}
fclose($csv);

// Send the last batch if present
if (!empty($params['body'])) {
    printf("Indexed %d documents\n", $count);
    try {
        $responses = $client->bulk($params);
    } catch (ElasticsearchException $e) {
        printf("Error: failed to bulk data with _id from %d to %d\n", $lastId, $count);
        exit(1);
    }
}

printf("Done\n");
printf("Took %.2f sec and %d MB RAM\n", microtime(true) - $start, memory_get_usage(true) / 1048576);