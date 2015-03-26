Webmozart Expression
====================

[![Build Status](https://travis-ci.org/webmozart/expression.svg?branch=master)](https://travis-ci.org/webmozart/expression)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/bd5a6248-0756-47d4-840d-621a33e3da5b/mini.png)](https://insight.sensiolabs.com/projects/bd5a6248-0756-47d4-840d-621a33e3da5b)
[![Latest Stable Version](https://poser.pugx.org/webmozart/expression/v/stable.svg)](https://packagist.org/packages/webmozart/expression)
[![Total Downloads](https://poser.pugx.org/webmozart/expression/downloads.svg)](https://packagist.org/packages/webmozart/expression)
[![Dependency Status](https://www.versioneye.com/php/webmozart:expression/1.0.0/badge.svg)](https://www.versioneye.com/php/webmozart:expression/1.0.0)

Latest release: [1.0.0-beta](https://packagist.org/packages/webmozart/expression#1.0.0-beta)

PHP >= 5.3.9

With this library, you can easily filter results of your domain services using
logical expressions.

Installation
------------

Use [Composer] to install the package:

```
$ composer require webmozart/expression:~1.0@beta
```

Usage
-----

Use the [`Expression`] interface in finder methods of your service classes:

```php
use Webmozart\Expression\Expression;

interface PersonRepository
{
    public function findPersons(Expression $expr);
}
```

When querying persons from the repository, you can create new expressions with
the [`Expr`] factory class:

```php
$expr = Expr::startsWith(Person::FIRST_NAME, 'Tho')
    ->andGreaterThan(Person::AGE, 35);
    
$persons = $repository->findPersons($expr);
```

Add the constants for the available search fields and a method `match()` to
your domain object:

```php
class Person
{
    const FIRST_NAME = 'firstName';
    
    const AGE = 'age';
    
    // ...
    
    public function match(Expression $expr)
    {
        return $expr->evaluate(array(
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
    
    public function findPersons(Expression $expr)
    {
        $result = array();
        
        foreach ($this->persons as $person) {
            if ($person->match($expr)) {
                $result[] = $person;
            }
        }
        
        return $result;
    }
}
```

Basic Expression
----------------

The [`Expr`] class is able to create the following expressions:

### Field Expressions

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

### Array Expressions

Method                                               | Description
---------------------------------------------------- | --------------------------------------------------------
`keyExists($field, $key)`                            | Check that a key exists 
`keyNotExists($field, $key)`                         | Check that a key does not exist 
`keyNull($field, $key)`                              | Check that a key is `null` 
`keyNotNull($field, $key)`                           | Check that a key is not `null` 
`keyEmpty($field, $key)`                             | Check that a key is empty (using `empty()`) 
`keyNotEmpty($field, $key)`                          | Check that a key is not empty (using `empty()`) 
`keyTrue($field, $key, $strict = true)`              | Check that a key is `true` 
`keyFalse($field, $key, $strict = true)`             | Check that a key is `false`
`keyEquals($field, $key, $value)`                    | Check that a key equals a value (using `==`) 
`keyNotEquals($field, $key, $value)`                 | Check that a key does not equal a value (using `!=`) 
`keySame($field, $key, $value)`                      | Check that a key is identical to a value (using `===`) 
`keyNotSame($field, $key, $value)`                   | Check that a key does not equal a value (using `!==`) 
`keyGreaterThan($field, $key, $value)`               | Check that a key is greater than a value 
`keyGreaterThanEqual($field, $key, $value)`          | Check that a key is greater than or equal to a value 
`keyLessThan($field, $key, $value)`                  | Check that a key is less than a value 
`keyLessThanEqual($field, $key, $value)`             | Check that a key is less than or equal to a value 
`keyStartsWith($field, $key, $prefix)`               | Check that a key starts with a given string 
`keyEndsWith($field, $key, $suffix)`                 | Check that a key ends with a given string 
`keyMatches($field, $key, $regExp)`                  | Check that a key matches a regular expression 
`keyOneOf($field, $key, $values, $strict = true)`    | Check that a key contains one of a list of values
`key($field, $key, Expression $expr)`                | Check that a key matches some expression

Logical Operators
-----------------

You can negate an expression with `not()`:

```php
$expr = Expr::not(Expr::startsWith(Person::FIRST_NAME, 'Tho'));
```

You can connect multiple expressions with "and" using the `and*()` methods:

```php
$expr = Expr::startsWith(Person::FIRST_NAME, 'Tho')
    ->andGreaterThan(Person::AGE, 35);
```

The same is possible for the "or" operator:

```php
$expr = Expr::startsWith(Person::FIRST_NAME, 'Tho')
    ->orGreaterThan(Person::AGE, 35);
```

If you want to mix and match "and" and "or" operators, use `andX()` and `orX()`
to add embedded expressions:

```php
$expr = Expr::startsWith(Person::FIRST_NAME, 'Tho')
    ->andX(
        Expr::greaterThan(Person::AGE, 35)
            ->orLessThan(Person::AGE, 20)
    );
    
$expr = Expr::startsWith(Person::FIRST_NAME, 'Tho')
    ->orX(
        Expr::notEmpty(Person::FIRST_NAME)
            ->andGreaterThan(Person::AGE, 35)
    );
```

Testing
-------

To make sure that PHPUnit compares [`Expression`] objects correctly, you should 
register the [`ExpressionComparator`] with PHPUnit in your PHPUnit bootstrap file:

```php
// tests/bootstrap.php
use SebastianBergmann\Comparator\Factory;
use Webmozart\Expression\PhpUnit\ExpressionComparator;

require_once __DIR__.'/../vendor/autoload.php';

Factory::getInstance()->register(new ExpressionComparator());
```

Make sure the file is registered correctly in `phpunit.xml.dist`:

```xml
<!-- phpunit.xml.dist -->
<?xml version="1.0" encoding="UTF-8"?>

<phpunit bootstrap="tests/bootstrap.php" colors="true">
    <!-- ... -->
</phpunit>
```

The [`ExpressionComparator`] makes sure that PHPUnit compares different 
[`Expression`] instances by *logical equivalence* instead of by object equality. 
For example, the following [`Expression`] are logically equivalent, but not equal 
as objects:
 
```php
// Logically equivalent
$c1 = Expr::notNull(Person::FIRST_NAME)->andSame(Person::AGE, 35);
$c2 = Expr::same(Person::AGE, 35)->andNotNull(Person::FIRST_NAME);

$c1 == $c2;
// => false

$c1->equals($c2);
// => true

// Also logically equivalent
$c1 = Expr::same(Person::AGE, 35);
$c2 = Expr::oneOf(Person::AGE, array(35));

$c1 == $c2;
// => false

$c1->equals($c2);
// => true
```

Expression Transformation
-------------------------

In some cases, you will want to transform expressions to some other
representation. A prime example is the transformation of an expression to a
[Doctrine] query.

You can implement a custom [`ExpressionVisitor`] to do the transformation. The 
visitor's methods `enterExpression()` and `leaveExpression()` are called for 
every node of the expression tree:

```php
use Webmozart\Expression\Traversal\ExpressionVisitor;

class QueryBuilderVisitor implements ExpressionVisitor
{
    private $qb;
    
    public function __construct(QueryBuilder $qb)
    {
        $this->qb = $qb;
    }
    
    public function enterExpression(Expression $expr)
    {
        // configure the $qb...
    }
    
    public function leaveExpression(Expression $expr)
    {
        // configure the $qb...
    }
}
```

Use an [`ExpressionTraverser`] to traverse an expression with your visitor:

```php
public function expressionToQueryBuilder(Expression $expr)
{
    $qb = new QueryBuilder();
    
    $traverser = new ExpressionTraverser();
    $traverser->addVisitor(new QueryBuilderVisitor($qb));
    $traverser->traverse($expr);
    
    return $qb;
}
```

Authors
-------

* [Bernhard Schussek] a.k.a. [@webmozart]
* [The Community Contributors]

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
[Doctrine]: http://www.doctrine-project.org/
[Bernhard Schussek]: http://webmozarts.com
[The Community Contributors]: https://github.com/webmozart/expression/graphs/contributors
[issue tracker]: https://github.com/webmozart/expression
[Git repository]: https://github.com/webmozart/expression
[@webmozart]: https://twitter.com/webmozart
[MIT license]: LICENSE
[`Expression`]: src/Expression.php
[`Expr`]: src/Expr.php
[`ExpressionComparator`]: src/PhpUnit/ExpressionComparator.php
[`ExpressionTraverser`]: src/Traversal/ExpressionTraverser.php
[`ExpressionVisitor`]: src/Traversal/ExpressionVisitor.php
