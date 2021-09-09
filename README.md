## Elasticsearch PHP examples

This project is a collection of examples on how to program Elasticsearch
in PHP.

All the examples use [elastic/elasticsearch-php](https://github.com/elastic/elasticsearch-php),
the official PHP client from [Elastic](https://www.elastic.co/).

## Install the examples

In order to run the example you need to install the dependencies using [composer](https://getcomposer.org/).
You need to have composer installed in your machine and run the following command:

```bash
composer install
```

This will create a `vendor` folder with all the libraries needed to execute the examples.

## How to run Elasticsearch

All the examples use a single node instance of Elasticsearch running at `localhost:9200`.
You can run this Elasticsearch node using the following command:

```bash
composer run-script es-run
```

This command will execute Elasticsearch on `localhost:9200` using [Docker](https://www.docker.com/).
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

## List of examples

- [Info](#info), [src/info.php](src/info.php)
- [Index](#index), [src/index.php](src/index.php)
- [Async index](#async-index), [src/async_index.php](src/async_index.php)
- [Bulk](#bulk), [src/bulk.php](src/bulk.php)

### Info

The [src/info.php](src/info.php) script returns the information about the Elasticsearch
instance, like the version number, the cluster name, etc.

### Index

This is the most basic API to store a single (JSON) document in Elasticsearch.
The [src/index.php](src/index.php) script stores a stock document in Elasticsearch using
the `stocks` index.

### Async index

### Update

### Delete


### Bulk

The [src/bulk.php](src/bulk.php) script stores 619,041 stock values in the `stocks` index.
The script collects 5,000 document at time and send it using the [Bulk API](https://www.elastic.co/guide/en/elasticsearch/reference/current/docs-bulk.html).
It performs 124 HTTP requests instead of 619,041, needed using the [Index API](https://www.elastic.co/guide/en/elasticsearch/reference/7.14/docs-index_.html).
We tested this script using an Intel Core i9 CPU with PHP 7.4, it required 8 seconds
and 166 MB RAM for the execution.

## License

All the code is released under the [Apache 2.0 license](README.md).

Copyright (c) Elasticsearch B.V (https://www.elastic.co)