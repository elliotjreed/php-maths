[![Contributor Covenant](https://img.shields.io/badge/Contributor%20Covenant-v2.0%20adopted-ff69b4.svg)](code-of-conduct.md)

# Maths

This PHP package provides an object-oriented wrapper for `bcmath` functionality and other common operations for dealing with numbers.

One aim of this project is to provide a greater degree of accuracy when dealing with floating point numbers.

## Installation

PHP 8.2 or above is required. For PHP 8.1 use version 6.2.0.

To install the package via [Composer](https://getcomposer.org/), run:

```shell
composer require elliotjreed/maths
```

or include the dependency in your `composer.json` file, for example:

```json
  "require": {
    "php": "^8.2",
    "ext-bcmath": "*",
    "elliotjreed/maths": "^7.0"
  }
```

## Usage

There are two base classes, `Number` and `NumberImmutable`.

Both can take any numeric value in the constructor (i.e. a numeric string such as `'1.5'`, an integer such as `150`, a float such as `1.33`, or a scientific notation string such as `'8.431e-05'`).

The result can be returned as a string, integer, or float.

For an example of the differences between `Number` and `NumberImmutable`:

```php
use ElliotJReed\Maths\NumberImmutable;
use ElliotJReed\Maths\Number;

$numberImmutable = new NumberImmutable(10);
$newNumberImmutable = $number->multiply(3);
$numberImmutable->asFloat(); // 10.0
$newNumberImmutable->asFloat(); // 30.0

$number = new Number(15);
$newNumber = $number->multiply(3);
$number->asFloat(); // 45.0
$newNumber->asFloat(); // 45.0
```

### Number

The `Number` object is mutable.

Examples:

```php
use ElliotJReed\Maths\Number;

$number = new Number('123.5');

$number->multiply(2);
$number->add(3);
$number->divide(10);
$number->subtract(20);

$number->asFloat(); // 25.0
$number->asInteger(); // 25
$number->asString(); // '25'
$number->asString(1); // '25.0'
$number->asString(2); // '25.00'
$number->asString(3); // '25.000'

$number->isEqualTo(25); // true
$number->isLessThan(30); // true
$number->isGreaterThan(20); // true
$number->isGreaterThanOrEqualTo(25); // true
$number->isLessThanOrEqualTo(25); // true
$number->isZero(); // false
```

```php
use ElliotJReed\Maths\Number;

$number = new Number(123.5);

$number->multiply(2)->add(3)->divide(10)->subtract(20);

$number->asFloat(); // 25.0
$number->asInteger(); // 25
$number->asString(); // '25'
```

Numbers can be `int`, `float`, `string`, or instances of `Number`.

```php
use ElliotJReed\Maths\Number;

$number = new Number(123.5);

$number->multiply(new Number(2));
$number->add('3');
$number->divide(10.0);
$number->subtract(20);

$number->asFloat(); // 25.0
$number->asInteger(); // 25
$number->asString(); // '25'
```

```php
use ElliotJReed\Maths\Number;

$number = new Number(10);
$anotherNumber = new Number($number);

$number->asFloat(); // 10.0
$anotherNumber->asFloat(); // 10.0

$number->add(10)->asFloat(); // 20.0
$anotherNumber->add(5)->asFloat(); // 15.0
```

```php
use ElliotJReed\Maths\Number;

$number = new Number(1.125);

$number->roundToDecimalPlaces(2);

$number->asFloat(); // 1.13
$number->asInteger(); // 1
$number->asString(); // '1.13'
```

```php
use ElliotJReed\Maths\Number;

$number = new Number(25);

$number->squareRoot();

$number->asFloat(); // 5.0
$number->asInteger(); // 5
$number->asString(); // '5'
```

```php
use ElliotJReed\Maths\Number;

$number = new Number(5);

$number->raiseToPower(2);

$number->asFloat(); // 5.0
$number->asInteger(); // 5
$number->asString(); // '5'
```

```php
use ElliotJReed\Maths\Number;

$number = new Number('25');

$number->increaseByPercentage(10);

$number->asFloat(); // 27.5
$number->asInteger(); // 28
$number->asInteger(PHP_ROUND_HALF_DOWN); // 27
$number->asString(); // '27.5'
$number->asString(2); // '27.50'
```

```php
use ElliotJReed\Maths\Number;

$number = new Number(5.5);

$number->modulus(2.5);

$number->asFloat(); // 0.5
$number->asInteger(); // 1
$number->asInteger(PHP_ROUND_HALF_DOWN); // 0
$number->asString(); // '0.5'
$number->asString(2); // '0.50'
```

```php
use ElliotJReed\Maths\Number;

$number = new Number('8.431e-05');

$number->asFloat(); // 0.00008431
$number->asString(); // '0.00008431'
```

### NumberImmutable

The `NumberImmutable` class is immutable.

Examples:

```php
use ElliotJReed\Maths\NumberImmutable;

$number = new NumberImmutable('123.5');

$number = $number->multiply(2);
$number = $number->add(3);
$number = $number->divide(10);
$number = $number->subtract(20);

$number->asFloat(); // 25.0
$number->asInteger(); // 25
$number->asString(); // '25'
$number->asString(1); // '25.0'
$number->asString(2); // '25.00'
$number->asString(3); // '25.000'

$number->isEqualTo(25); // true
$number->isLessThan(30); // true
$number->isGreaterThan(20); // true
$number->isGreaterThanOrEqualTo(25); // true
$number->isLessThanOrEqualTo(25); // true
$number->isZero(); // false
```

```php
use ElliotJReed\Maths\NumberImmutable;

$number = new NumberImmutable(123.5);

$number = $number->multiply(2)->add(3)->divide(10)->subtract(20);

$number->asFloat(); // 25.0
$number->asInteger(); // 25
$number->asString(); // '25'
```

Numbers can be `int`, `float`, `string`, or instances of `Number`.

```php
use ElliotJReed\Maths\NumberImmutable;

$number = new NumberImmutable(123.5);

$number = $number->multiply(new NumberImmutable(2));
$number = $number->add('3');
$number = $number->divide(10.0);
$number = $number->subtract(20);

$number->asFloat(); // 25.0
$number->asInteger(); // 25
$number->asString(); // '25'
```

```php
use ElliotJReed\Maths\NumberImmutable;

$number = new NumberImmutable(1.125);

$number = $number->roundToDecimalPlaces(2);

$number->asFloat(); // 1.13
$number->asInteger(); // 1
$number->asString(); // '1.13'
```

```php
use ElliotJReed\Maths\NumberImmutable;

$number = new NumberImmutable(25);

$number = $number->squareRoot();

$number->asFloat(); // 5.0
$number->asInteger(); // 5
$number->asString(); // '5'
```

```php
use ElliotJReed\Maths\NumberImmutable;

$number = new NumberImmutable(5);

$number = $number->raiseToPower(2);

$number->asFloat(); // 5.0
$number->asInteger(); // 5
$number->asString(); // '5'
```

```php
use ElliotJReed\Maths\NumberImmutable;

$number = new NumberImmutable('25');

$number = $number->increaseByPercentage(10);

$number->asFloat(); // 27.5
$number->asInteger(); // 28
$number->asInteger(PHP_ROUND_HALF_DOWN); // 27
$number->asString(); // '27.5'
$number->asString(2); // '27.50'
```

```php
use ElliotJReed\Maths\NumberImmutable;

$number = new NumberImmutable(5.5);

$number = $number->modulus(2.5);

$number->asFloat(); // 0.5
$number->asInteger(); // 1
$number->asInteger(PHP_ROUND_HALF_DOWN); // 0
$number->asString(); // '0.5'
$number->asString(2); // '0.50'
```

```php
use ElliotJReed\Maths\NumberImmutable;

$number = new NumberImmutable('8.431e-05');

$number->asFloat(); // 0.00008431
$number->asString(); // '0.00008431'
```

## Development

### Getting Started

PHP 8.1 or above and Composer is expected to be installed.

#### Installing Composer

For instructions on how to install Composer visit [getcomposer.org](https://getcomposer.org/download/).

#### Installing

After cloning this repository, change into the newly created directory and run:

```bash
composer install
```

or if you have installed Composer locally in your current directory:

```bash
php composer.phar install
```

This will install all dependencies needed for the project.

Henceforth, the rest of this README will assume `composer` is installed globally (ie. if you are using `composer.phar` you will need to use `composer.phar` instead of `composer` in your terminal / command-line).

### Running the Tests

#### Unit tests

Unit testing in this project is via [PHPUnit](https://phpunit.de/).

All unit tests can be run by executing:

```bash
composer phpunit
```

##### Debugging

To have PHPUnit stop and report on the first failing test encountered, run:

```bash
composer phpunit:debug
```

### Code formatting

A standard for code style can be important when working in teams, as it means that less time is spent by developers processing what they are reading (as everything will be consistent).

Code formatting is automated via [PHP-CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer).
PHP-CS-Fixer will not format line lengths which do form part of the PSR-2 coding standards so these will product warnings when checked by [PHP Code Sniffer](https://github.com/squizlabs/PHP_CodeSniffer).

These can be run by executing:

```bash
composer phpcs
```

#### Running everything

All of the tests can be run by executing:

```bash
composer test
```

### Outdated dependencies

Checking for outdated Composer dependencies can be performed by executing:

```bash
composer outdated
```

#### Validating Composer configuration

Checking that the [composer.json](composer.json) is valid can be performed by executing:

```bash
composer validate --no-check-publish
```

#### Running via GNU Make

If GNU [Make](https://www.gnu.org/software/make/) is installed, you can replace the above `composer` command prefixes with `make`.

All of the tests can be run by executing:

```bash
make test
```

#### Running the tests on a Continuous Integration platform (eg. Github Actions)

Specific output formats better suited to CI platforms are included as Composer scripts.

To output unit test coverage in text and Clover XML format (which can be used for services such as [Coveralls](https://coveralls.io/)):

```
composer phpunit:ci
```

To output PHP-CS-Fixer (dry run) and PHPCS results in checkstyle format (which GitHub Actions will use to output a readable format):

```
composer phpcs:ci
```

##### Github Actions

Look at the example in [.github/workflows/main.yml](.github/workflows/main.yml).

## Built With

  - [PHP](https://secure.php.net/)
  - [Composer](https://getcomposer.org/)
  - [PHPUnit](https://phpunit.de/)
  - [PHP Code Sniffer](https://github.com/squizlabs/PHP_CodeSniffer)
  - [GNU Make](https://www.gnu.org/software/make/)

## License

This project is licensed under the MIT License - see the [LICENCE.md](LICENCE.md) file for details.
