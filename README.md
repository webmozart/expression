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

If you want to mix and match "and" and "or" operators, use `andX()` and `orX()`
to add embedded criteria:

```php
$criteria = Criterion::startsWith(Person::FIRST_NAME, 'Tho')
    ->andX(
        Criterion::greaterThan(Person::AGE, 35)
            ->orLessThan(Person::AGE, 20);
    );
    
$criteria = Criterion::startsWith(Person::FIRST_NAME, 'Tho')
    ->orX(
        Criterion::notEmpty(Person::FIRST_NAME)
            ->andGreaterThan(Person::AGE, 35);
    );
```

Testing
-------

To make sure that PHPUnit compares [`Criteria`] objects correctly, you should 
call the `register()` method of [`CriteriaComparator`] in the 
`setUpBeforeClass()` method of all tests that compare [`Criteria`] instances:

```php
use Webmozart\Criteria\Criterion;
use Webmozart\Criteria\PhpUnit\CriteriaComparator;

class PersonManagerTest extends PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        CriteriaComparator::register();
    }
    
    public function testManagePersons()
    {
        $repository = $this->getMock('PersonRepository');
        
        $repository->expects($this->once())
            ->method('findPersons')
            ->with(Criterion::startsWith(Person::FIRST_NAME, 'Tho')
                ->andGreaterThan(Person::AGE, 35))
            ->willReturn(array(
                // ...
            ));
            
        // ...
    }
}
```

The [`CriteriaComparator`] makes sure that the [`Criteria`] created in the test
and the [`Criteria`] created in the implementation are compared by *logical
equivalence* instead of object equality. For example, the following [`Criteria`] 
are logically equivalent, but not equal as objects:
 
```php
// Logically equivalent
$c1 = Criterion::notNull(Person::FIRST_NAME)->andSame(Person::AGE, 35);
$c2 = Criterion::same(Person::AGE, 35)->andNotNull(Person::FIRST_NAME);

$c1 == $c2;
// => false

$c1->equals($c2);
// => true

// Also logically equivalent
$c1 = Criterion::same(Person::AGE, 35);
$c2 = Criterion::oneOf(Person::AGE, array(35));

$c1 == $c2;
// => false

$c1->equals($c2);
// => true
```

Authors
-------

* [Bernhard Schussek] a.k.a. [@webmozart]
* [The Community Contributors]

Installation
------------

Use [Composer] to install the package:

```
$ composer require webmozart/criteria@dev
```

Contribute
----------

Contributions to the package are always welcome!

* Report any bugs or issues you find on the [issue tracker].
* You can grab the source code at the package's [Git repository].

Support
-------

If you are having problems, send a mail to bschussek@gmail.com or shout out to
[@webmozart] on Twitter.

License
-------

All contents of this package are licensed under the [MIT license].

[Composer]: https://getcomposer.org
[Bernhard Schussek]: http://webmozarts.com
[The Community Contributors]: https://github.com/webmozart/criteria/graphs/contributors
[issue tracker]: https://github.com/webmozart/criteria
[Git repository]: https://github.com/webmozart/criteria
[@webmozart]: https://twitter.com/webmozart
[MIT license]: LICENSE
[`Criteria`]: src/Criteria.php
[`Criterion`]: src/Criterion.php
