## Elasticsearch for PHP examples

This project is a collection of examples on how to use [Elasticsearch](https://github.com/elastic/elasticsearch) in PHP.

All the examples use [elasticsearch-php](https://github.com/elastic/elasticsearch-php), the official PHP client from [Elastic](https://www.elastic.co/). 

## Install the examples

In order to run the examples you need to install the dependencies using [composer](https://getcomposer.org/).

After [installing](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos)
composer in your machine and run the following command:

```bash
composer install
```

This will create a `vendor` folder with all the libraries needed to execute the examples.

## How to run Elasticsearch

You need to have an Elasticsearch running instance to execute the examples.
You can read in the [official documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/setup.html) how to setup Elasticsearch.

## ES|QL

You need to import the `data/books.csv` file in Elasticsearch.

You can run the [bulk.php](bulk.php) script as follows:

```bash
php bulk.php
```

This will create a `books` index in Elasticsearch with the following mapping:

```json
{
    "mappings" : {
        "properties": {
            "title": {
                "type": "text"
            },
            "description": {
                "type": "text"
            },
            "author": {
                "type": "text"
            },
            "year": {
                "type": "short"
            },
            "publisher": {
                "type": "keyword"
            },
            "rating": {
                "type": "half_float"
            }
        }
    }
}
```

After you can run the [query.php](query.php) example to retrieve the top-10
books written by Stephen King, according to the Amazon customer reviews.

The command as follows:

```bash
php query.php
```

You should see an output as follows:

```
Books in index: 81828
Stephen King, How writers write, 2002, Rating: 5.00
Stephen King, Blockade, 2010, Rating: 5.00
Stephen King, THE ELEMENTS OF STYLE, 2013, Rating: 5.00
Stephen King, Night Shift (Signet), 1979, Rating: 4.56
Stephen King, The Stand. The Complete and Uncut Edition, 1990, Rating: 4.46
Stephen King, The Drawing of the 3: The Dark Tower II, 1987, Rating: 4.44
Stephen King, Misery, 2016, Rating: 4.43
Stephen King, Misery (Spanish Edition), 2016, Rating: 4.43
Stephen King, Christine, 2011, Rating: 4.42
Stephen King, The Dead Zone, 2016, Rating: 4.40
```

By default, the `query.php` script build an array of [stdClass](https://www.php.net/manual/en/class.stdclass.php) objects.
If you comment line [35](https://github.com/elastic/elasticsearch-php-examples/blob/main/examples/ESQL/query.php#L35) and uncomment line [36](https://github.com/elastic/elasticsearch-php-examples/blob/main/examples/ESQL/query.php#L36), you can create an array of [Book](Book.php) objects.

## License

All the code is released under the [Apache 2.0 license](README.md).

Copyright (c) Elasticsearch B.V (https://www.elastic.co)