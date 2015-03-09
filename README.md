Webmozart Criteria
==================

[![Build Status](https://travis-ci.org/webmozart/criteria.svg?branch=master)](https://travis-ci.org/webmozart/criteria)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/webmozart/criteria/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/webmozart/criteria/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/00d2acd0-d0b7-458f-abca-278f79f0c2ed/mini.png)](https://insight.sensiolabs.com/projects/00d2acd0-d0b7-458f-abca-278f79f0c2ed)
[![Latest Stable Version](https://poser.pugx.org/webmozart/criteria/v/stable.svg)](https://packagist.org/packages/webmozart/criteria)
[![Total Downloads](https://poser.pugx.org/webmozart/criteria/downloads.svg)](https://packagist.org/packages/webmozart/criteria)
[![Dependency Status](https://www.versioneye.com/php/webmozart:criteria/1.0.0/badge.svg)](https://www.versioneye.com/php/webmozart:criteria/1.0.0)

Latest release: none

PHP >= 5.3.9

With this library, you can easily filter results of your domain services using
logical expressions.

Usage
-----

Use the [`Criteria`] interface in finder methods of your service classes:

```php
use Webmozart\Criteria\Criteria;

interface PersonRepository
{
    public function findPersons(Criteria $criteria);
}
```

When querying persons from the repository, you can create new search criteria
with the [`Criterion`] factory class:

```php
$criteria = Criterion::startsWith(Person::FIRST_NAME, 'Tho')
    ->andGreaterThan(Person::AGE, 35);
    
$persons = $repository->findPersons($criteria);
```

Add the constants for the available search fields and a method `match()` to
your domain object:

```php
class Person
{
    const FIRST_NAME = 'firstName';
    
    const AGE = 'age';
    
    // ...
    
    public function match(Criteria $criteria)
    {
        return $criteria->match(array(
            self::FIRST_NAME => $this->firstName,
            self::AGE => $this->age,
            // ...
        ));
    }
}
```

The repository implementation can use this method to match individual persons
against the criteria:

```php
class PersonRepositoryImpl implements PersonRepository
{
    private $persons = array();
    
    public function findPersons(Criteria $criteria)
    {
        $result = array();
        
        foreach ($this->persons as $person) {
            if ($person->match($criteria)) {
                $result[] = $person;
            }
        }
        
        return $result;
    }
}
```

Basic Criteria
--------------

The [`Criterion`] class is able to create the following basic criteria:

Method                                      | Description
------------------------------------------- | --------------------------------------------------------
`null($field)`                              | Check that a field is `null` 
`notNull($field)`                           | Check that a field is not `null` 
`isEmpty($field)`                           | Check that a field is empty (using `empty()`) 
`notEmpty($field)`                          | Check that a field is not empty (using `empty()`) 
`true($field, $strict = true)`              | Check that a field is `true` 
`false($field, $strict = true)`             | Check that a field is `false`
`equals($field, $value)`                    | Check that a field equals a value (using `==`) 
`notEquals($field, $value)`                 | Check that a field does not equal a value (using `!=`) 
`same($field, $value)`                      | Check that a field is identical to a value (using `===`) 
`notSame($field, $value)`                   | Check that a field does not equal a value (using `!==`) 
`greaterThan($field, $value)`               | Check that a field is greater than a value 
`greaterThanEqual($field, $value)`          | Check that a field is greater than or equal to a value 
`lessThan($field, $value)`                  | Check that a field is less than a value 
`lessThanEqual($field, $value)`             | Check that a field is less than or equal to a value 
`startsWith($field, $prefix)`               | Check that a field starts with a given string 
`endsWith($field, $suffix)`                 | Check that a field ends with a given string 
`matches($field, $regExp)`                  | Check that a field matches a regular expression 
`oneOf($field, $values, $strict = true)`    | Check that a field contains one of a list of values

Logical Operators
-----------------

You can negate some criteria with `not()`:

```php
$criteria = Criterion::not(Criterion::startsWith(Person::FIRST_NAME, 'Tho'));
```

You can connect multiple criteria with "and" using the `and*()` methods:

```php
$criteria = Criterion::startsWith(Person::FIRST_NAME, 'Tho')
    ->andGreaterThan(Person::AGE, 35);
```

The same is possible for the "or" operator:

```php
$criteria = Criterion::startsWith(Person::FIRST_NAME, 'Tho')
    ->orGreaterThan(Person::AGE, 35);
```

If you want to mix and match "and" and "or" operators, use `andCriteria()`
and `orCriteria()` to add embedded criteria:

```php
$criteria = Criterion::startsWith(Person::FIRST_NAME, 'Tho')
    ->andCriteria(
        Criterion::greaterThan(Person::AGE, 35)
            ->orLessThan(Person::AGE, 20);
    );
    
$criteria = Criterion::startsWith(Person::FIRST_NAME, 'Tho')
    ->orCriteria(
        Criterion::notEmpty(Person::FIRST_NAME)
            ->andGreaterThan(Person::AGE, 35);
    );
```

Authors
-------

* [Bernhard Schussek] a.k.a. [@webmozart]
* [The Community Contributors]

Installation
------------

Installation
------------

Use [Composer] to install the package:

```
$ composer require webmozart/key-value-store@dev
```

Contribute
----------

Contributions to are very welcome!

* Report any bugs or issues you find on the [issue tracker].
* You can grab the source code at Puli’s [Git repository].

Support
-------

If you are having problems, send a mail to bschussek@gmail.com or shout out to
[@webmozart] on Twitter.

License
-------

All contents of this package are licensed under the [MIT license].

[Puli]: http://webmozart.io
[Bernhard Schussek]: http://webmozarts.com
[The Community Contributors]: https://github.com/webmozart/criteria/graphs/contributors
[Getting Started]: http://docs.webmozart.io/en/latest/getting-started.html
[Puli Documentation]: http://docs.webmozart.io/en/latest/index.html
[issue tracker]: https://github.com/webmozart/issues/issues
[Git repository]: https://github.com/webmozart/criteria
[@webmozart]: https://twitter.com/webmozart
[MIT license]: LICENSE
[`Criteria`]: src/Criteria.php
[`Criterion`]: src/Criterion.php
