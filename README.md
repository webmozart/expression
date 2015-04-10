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
$expr = Expr::startsWith('Tho', Person::FIRST_NAME)
    ->andGreaterThan(35, Person::AGE);
    
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

Expressions
-----------

The [`Expr`] class is able to create the following expressions:

Method                      | Description
--------------------------- | --------------------------------------------------------
`null()`                    | Check that a value is `null`
`notNull()`                 | Check that a value is not `null`
`isEmpty()`                 | Check that a value is empty (using `empty()`)
`notEmpty()`                | Check that a value is not empty (using `empty()`)
`true()`                    | Check that a value is `true`
`false()`                   | Check that a value is `false`
`equals($value)`            | Check that a value equals another value (using `==`)
`notEquals($value)`         | Check that a value does not equal another value (using `!=`)
`same($value)`              | Check that a value is identical to another value (using `===`)
`notSame($value)`           | Check that a value does not equal another value (using `!==`)
`greaterThan($value)`       | Check that a value is greater than another value
`greaterThanEqual($value)`  | Check that a value is greater than or equal to another value
`lessThan($value)`          | Check that a value is less than another value
`lessThanEqual($value)`     | Check that a value is less than or equal to another value
`startsWith($prefix)`       | Check that a value starts with a given string
`endsWith($suffix)`         | Check that a value ends with a given string
`matches($regExp)`          | Check that a value matches a regular expression
`in($values)`               | Check that a value occurs in a list of values
`keyExists($key)`           | Check that a key exists in a value
`keyNotExists($key)`        | Check that a key does not exist in a value

Selectors
---------

Every method in [`Expr`] accepts the name of the tested array key as last,
optional argument:

```php
$expr = Expr::greaterThan(10, 'age');

$expr->evaluate(array('age' => 10));
// => true
```

If the name of the key is omitted, the expression is evaluated directly for the
passed value:

```php
$expr = Expr::greaterThan(10);

$expr->evaluate(12);
// => true
```

Passing the array key is equivalent to using the `key()` selector of the
[`Expr`] class:

```php
$expr = Expr::greaterThan(10, 'age');

// same as
$expr = Expr::key('age', Expr::greaterThan(10));
```

By using the `key()` method directly, you can evaluate expressions for
multi-dimensional arrays:

```php
$expr = Expr::key('parameters', Expr::key('age', Expr::greaterThan(10)));

$expr->evaluate(array(
    'parameters' => array(
        'age' => 12,
    ),
));
// => true
```

The [`Expr`] class features several other selectors similar to `key()`. The
following table lists them all:

Method                      | Description
--------------------------- | -------------------------------------------------------------------------------
`key($key, $expr)`          | Evaluate an expression for a key of an array
`atLeast($count, $expr)`    | Check that an expression matches for at least `$count` entries of a traversable
`atMost($count, $expr)`     | Check that an expression matches for at most `$count` entries of a traversable
`exactly($count, $expr)`    | Check that an expression matches for exactly `$count` entries of a traversable
`all($expr)`                | Check that an expression matches for all entries of a traversable
`count($expr)`              | Check that an expression matches for the count of a collection

Logical Operators
-----------------

You can negate an expression with `not()`:

```php
$expr = Expr::not(Expr::startsWith('Tho', Person::FIRST_NAME));
```

You can connect multiple expressions with "and" using the `and*()` methods:

```php
$expr = Expr::startsWith('Tho', Person::FIRST_NAME)
    ->andGreaterThan(35, Person::AGE);
```

The same is possible for the "or" operator:

```php
$expr = Expr::startsWith('Tho', Person::FIRST_NAME)
    ->orGreaterThan(35, Person::AGE);
```

If you want to mix and match "and" and "or" operators, use `andX()` and `orX()`
to add embedded expressions:

```php
$expr = Expr::startsWith('Tho', Person::FIRST_NAME)
    ->andX(
        Expr::greaterThan(35, Person::AGE)
            ->orLessThan(20, Person::AGE)
    );
    
$expr = Expr::startsWith('Tho', Person::FIRST_NAME)
    ->orX(
        Expr::notEmpty(Person::FIRST_NAME)
            ->andGreaterThan(35, Person::AGE)
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
$c1 = Expr::notNull(Person::FIRST_NAME)->andSame(35, Person::AGE);
$c2 = Expr::same(35, Person::AGE)->andNotNull(Person::FIRST_NAME);

$c1 == $c2;
// => false

$c1->equals($c2);
// => true

// Also logically equivalent
$c1 = Expr::same(35, Person::AGE);
$c2 = Expr::oneOf(array(35), Person::AGE);

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
