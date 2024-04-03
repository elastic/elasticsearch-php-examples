<?php

require 'vendor/autoload.php';

use Elastic\Elasticsearch\ClientBuilder;

$client = ClientBuilder::create()
            ->build();

// Count of books
$query = <<<EOD
    FROM books
    | STATS tot = COUNT(title)
EOD;

$result = $client->esql()->query([
    'body' => ['query' => $query]
]);

$books = $result->mapTo();
printf("Books in index: %d\n", $books[0]->tot);

// Search the top-10 books of Stephen King
$query = <<<EOD
    FROM books
    | WHERE author == "Stephen King"
    | SORT rating DESC
    | LIMIT 10
EOD;

$result = $client->esql()->query([
    'body' => ['query' => $query]
]);

$books = $result->mapTo(); // Array of stdClass
// $books = $result->mapTo(Book::class); // Array of Book

foreach ($books as $book) {
    printf(
        "%s, %s, %d, Rating: %.2f\n",
        $book->author,
        $book->title,
        $book->year,
        $book->rating
    );
}
