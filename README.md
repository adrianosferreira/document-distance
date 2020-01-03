# Document Distance - Cosine Similarity

[![Build Status](https://travis-ci.org/adrianosferreira/document-distance.svg?branch=master)](https://travis-ci.org/adrianosferreira/document-distance)
[![Total Downloads](https://poser.pugx.org/adrianoferreira/document-distance/downloads)](https://packagist.org/packages/adrianoferreira/document-distance)
[![License](https://poser.pugx.org/adrianoferreira/document-distance/license)](https://packagist.org/packages/adrianoferreira/document-distance)

Document Distance / Similarity is measured based on the content overlap between documents.

One of the most common algorithms to solve this particular problem is the cosine similarity - a vector based similarity measure. That's what this library is about.

The cosine distance of two documents is defined by the angle between their feature vectors which are, in our case, word frequency vectors. The word frequency distribution of a document is a mapping from words to their frequency count.

![Cosine Similarity](https://www.andrew.cmu.edu/course/15-121/labs/HW-4%20Document%20Distance/pix1.bmp)

## Installation

It's recommended that you use Composer to install this library.

```
$ composer require adrianoferreira/document-distance:dev-master
```

## Usage

Calculating similarity percentage between two local files:

```php
$test = new DocumentDistance();
echo $test->getFilesPercentageDistance( 'test.txt', 'test2.txt' );
```

Calculating arc size between two local files:

```php
$test = new DocumentDistance();
echo $test->getFilesArcSize( 'test.txt', 'test2.txt' );
```

Calculating similarity percentage between two arbitrary strings:

```php
$test = new DocumentDistance();
echo $test->getPercentageDistance( 'test 123 456', 'test 678 000' );
```

Calculating arc size between arbitrary strings:

```php
$test = new DocumentDistance();
echo $test->getFilesArcSize( 'test 123 456', 'test 678 000' );
```

You can also combine a local and a remote file:

```php
$test = new DocumentDistance();
echo $test->getFilesPercentageDistance( 'http://something.com/some-remote-file.txt', 'text.txt', true );
```

## References
This implementation is based in a MIT document: https://courses.csail.mit.edu/6.006/fall11/rec/rec02.pdf
