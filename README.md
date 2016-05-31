# php-lambda
[![Build Status](https://travis-ci.org/pldin601/php-lambda.svg?branch=master)](https://travis-ci.org/pldin601/php-lambda)
[![Code Climate](https://codeclimate.com/github/pldin601/php-lambda/badges/gpa.svg)](https://codeclimate.com/github/pldin601/php-lambda)
[![Test Coverage](https://codeclimate.com/github/pldin601/php-lambda/badges/coverage.svg)](https://codeclimate.com/github/pldin601/php-lambda/coverage)
[![Issue Count](https://codeclimate.com/github/pldin601/php-lambda/badges/issue_count.svg)](https://codeclimate.com/github/pldin601/php-lambda)

Generates lambda function using given string pattern.

## Example
```php
use function Lambda\l;

$sum = l('_ + _');

echo $sum(2, 4); // will output 6


$neg = l('-(_)');

echo $neg('11'); // will output -11


$numbers = range(1, 10);

$evens = array_map($numbers, l('_ % 2 == 0'));
```
