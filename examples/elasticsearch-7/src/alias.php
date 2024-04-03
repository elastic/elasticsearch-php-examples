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

$index = 'stocks';
$alias = 'stock-v1';

try {
    $result = $client->indices()->putAlias([
        'index' => $index,
        'name' => $alias
    ]);
} catch (ElasticsearchException $e) {
    printf("Error: failed to create alias %d of %d\n", $alias, $index);
    printf("%s\n", $e->getMessage());
    exit(1);
}

if ($result['acknowledged']) {
    printf("The alias %s has been created!\n", $alias);
}