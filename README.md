## Elasticsearch PHP examples

This project is a collection of examples on how to program [Elasticsearch](https://github.com/elastic/elasticsearch)
in PHP.

All the examples use [elastic/elasticsearch-php](https://github.com/elastic/elasticsearch-php),
the official PHP client from [Elastic](https://www.elastic.co/).

## Install the examples

In order to run the examples you need to install the dependencies using [composer](https://getcomposer.org/).
After [installing](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos)
composer in your machine and run the following command:

```bash
composer install
```

This will create a `vendor` folder with all the libraries needed to execute the examples.

## How to run Elasticsearch

You need to have an Elasticsearch instance to execute the examples. You can run a single node
instance of Elasticsearch running at `localhost:9200`, using the following command:

```bash
composer run-script es-run
```

This command will execute Elasticsearch 7.15.1 on `localhost:9200` using [Docker](https://www.docker.com/).
If you want to stop the Elasticsearch instance you can use the following command:

```bash
composer run-script es-stop
```

## Data set

All the examples are based on the the [all_stocks_5yr.csv](data/all_stocks_5yr.csv)
file containing 5 years of stock prices of 500 Fortune companies, starting from
February 2013.

The first two lines of this file contain the following data:

```
date,open,high,low,close,volume,name
2013-02-08,15.07,15.12,14.63,14.75,8407500,AAL
```

The information reported are the *date* (2013-02-08), the *open* value (15.07), the *high*
value (15.12), the *low* value (14.63), the *close* value (14.75), the *volume* of stock
exchanges (8,407,500) and the *name* of the stock (AAL = American Airlines Group).

### Index all the data set

Almost all the examples reported in `src/` uses an Elasticsearch index called `stocks`.
To insert all the stock prices reported in [all_stocks_5yr.csv](data/all_stocks_5yr.csv)
you can use the [bulk](#bulk) example.

## List of examples

- [Async index](#async-index), [src/async_index.php](src/async_index.php)
- [Bulk](#bulk), [src/bulk.php](src/bulk.php)
- [Index](#index), [src/index.php](src/index.php)
- [Info](#info), [src/info.php](src/info.php)

### Async index

This is the asynchronous version of the [Index](#index) example. The `elasticsearch-php`
library offers a [Future mode](https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/future_mode.html)
for performing any endpoints using an async call.

To execute an asynchronous call you need to add the following `client` value in the `$params`
input array:

```php
'client' => [
    'future' => 'lazy'
]
```

This returns a future, rather than the actual response. A future represents a future
computation and acts like a placeholder. You can pass a future around your code like
a regular object. When you need the result values, you can resolve the future.
The [src/async_index.php](src/async:index.php) script stores 3 stock documents in
Elasticsearch using an asynchronous approach.

### Bulk

The [src/bulk.php](src/bulk.php) script stores `619,041` stock values in the `stocks` index.
The script collects `5,000` document at time and send it using the [Bulk API](https://www.elastic.co/guide/en/elasticsearch/reference/current/docs-bulk.html).
It performs 124 HTTP requests instead of 619,041, needed using the [Index API](https://www.elastic.co/guide/en/elasticsearch/reference/7.14/docs-index_.html).
We tested this script using an Intel Core i9 CPU with PHP 7.4, it required 8 seconds
and 166 MB RAM for the execution.

### Custom mapping

The [src/custom_mapping.php](src/custom_mapping.php) script configure a custom mapping
for the `stocks` index. It changes the type of the `name` field in `keyword`.
This update can be useful to perform aggregation of the stock prices.

### Delete index

The [src/delete_index.php](src/delete_index.php) script deletes the `stocks` index.

### Get HTTP request and response

The [src/get_http_request_and_response.php](src/get_http_request_and_response.php) script
is an example of how to get the HTTP request and response using `elasticsearch-php`.
The example execute an `info()` API and returns the information about the HTTP request and
response using the function `getLastConnection()` from the Transport.

### Get mapping

The [src/get_mapping.php](src/get_mapping.php) script returns the mapping of the `stocks`
index. You can change the mapping using the [src/custom_mapping.php](src/custom_mapping.php)
script example.

### Index

This is the most basic API to store a single (JSON) document in Elasticsearch.
The [src/index.php](src/index.php) script stores a stock document in Elasticsearch using
the `stocks` index.

### Info

The [src/info.php](src/info.php) script returns the information about the Elasticsearch
instance, like the version number, the cluster name, etc.

### Logging

The [src/logging.php](src/logging.php) script is an example of how to enable the logging
feature of `elasticsearch-php` library. You can use any [PSR-3](https://www.php-fig.org/psr/psr-3/)
logger library. In the example we used [monolog](https://github.com/Seldaek/monolog).
The log will be created using `log/elasticsearch-php.log` file.


### Schema on read

The [src/schema_on_read.php](src/schema_on_read.php) script uses the [schema on read](https://www.elastic.co/blog/introducing-elasticsearch-runtime-fields)
feature available since Elasticsearch 7.11.

The schema on read can be used to create runtime fields that are not stored
in the index, they are created only in the HTTP response.

Runtime fields let you define and evaluate fields at query time, which opens a wide range of
new use cases. If you need to adapt to a changing log format or fix an index mapping, use
runtime fields to change the schema on the fly without reindexing your data. Or if you are
indexing new data and don’t have intimate knowledge of what it contains, you can use runtime
fields to discover this data and define your schema without impacting others.

In the example we created an `average` field with the following [painless](https://www.elastic.co/guide/en/elasticsearch/reference/master/modules-scripting-painless.html) 
script:
```
emit((double)(doc['high'].value + doc['low'].value)/2)
```

### Search aggregation

The [src/search_aggregation.php](src/search_aggregation.php) script is used to aggregate the
stock prices using the stock `name` field.

The example retrievs retrieving maximum 1000 values ordering the results by name.

### Search filter

The [src/search_filter.php](src/search_filter.php) script search for all the `AAL` stock
prices using a filter search term.

### Search fuzzy

The [src/search_fuzzy.php](src/search_fuzzy.php) script executes a [Fuzzy query](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-fuzzy-query.html).
A fuzzy search returns the results that contain terms similar to the search term,
as measured by a [Levenshtein edit distance](https://en.wikipedia.org/wiki/Levenshtein_distance).

In the example we used the name `AAL` as query that will returns also `AAP` values.

### Search highlight

The [src/search_highlight.php](src/search_highlight.php) script provide a query search
using the [highlighting](https://www.elastic.co/guide/en/elasticsearch/reference/current/highlighting.html)
feature of Elasticsearch to put evidence on the results.

In the example we search for the name `AAL` and the results will contain the `<em>AAL</em>`
tag that you can use to configure a CSS style.

### Search iterator

### Search match_all

### Search match page

### Search match

### Update mapping


## References

You can watch a recorded presentation about ["Programming Elasticsearch with PHP"](https://www.youtube.com/watch?v=r4xPItSnLxw)
provided by [Enrico Zimuel](https://www.zimuel.it) at PHP Conference Japan 2022. [Here](https://www.zimuel.it/talks/PHPJapan2021.pdf)
the slides of the presentation.

## License

All the code is released under the [Apache 2.0 license](README.md).

Copyright (c) Elasticsearch B.V (https://www.elastic.co)