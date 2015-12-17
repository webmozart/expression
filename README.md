Webmozart Expression
====================

[![Build Status](https://travis-ci.org/webmozart/expression.svg?branch=1.0.0)](https://travis-ci.org/webmozart/expression)
[![Build status](https://ci.appveyor.com/api/projects/status/6dstc380h5pr5rk3/branch/master?svg=true)](https://ci.appveyor.com/project/webmozart/expression/branch/master)
[![Latest Stable Version](https://poser.pugx.org/webmozart/expression/v/stable.svg)](https://packagist.org/packages/webmozart/expression)
[![Total Downloads](https://poser.pugx.org/webmozart/expression/downloads.svg)](https://packagist.org/packages/webmozart/expression)
[![Dependency Status](https://www.versioneye.com/php/webmozart:expression/1.0.0/badge.svg)](https://www.versioneye.com/php/webmozart:expression/1.0.0)

Latest release: [1.0.0](https://packagist.org/packages/webmozart/expression#1.0.0)

PHP >= 5.3.9

This library implements the [Specification Pattern] for PHP. You can use it to
easily filter results of your domain services by logical expressions.

Conversely to [rulerz], this library focuses on providing a usable and efficient
PHP API first. An expression language that converts string expressions into
`Expression` instances can be built on top, but is not included in the current
release.

Visitors can be implemented that convert `Expression` objects into Doctrine
queries and similar objects.

Installation
------------

Use [Composer] to install the package:

```
$ composer require webmozart/expression
```

Basic Usage
-----------

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
$expr = Expr::method('getFirstName', Expr::startsWith('Tho'))
    ->andMethod('getAge', Expr::greaterThan(35));
    
$persons = $repository->findPersons($expr);
```

The repository implementation can use the `evaluate()` method to match 
individual persons against the criteria:

```php
class PersonRepositoryImpl implements PersonRepository
{
    private $persons = [];
    
    public function findPersons(Expression $expr)
    {
        return Expr::filter($this->persons, $expr);
    }
}
```

Domain Expressions
------------------

Extend existing expressions to build domain-specific expressions:

```php
class IsPremium extends Method
{
    public function __construct()
    {
        parent::__construct('isPremium', [], Expr::same(true));
    }
}

class HasPreviousBookings extends Method
{
    public function __construct()
    {
        parent::__construct(
            'getBookings', 
            [], 
            Expr::count(Expr::greaterThan(0))
        );
    }
}

// Check if a customer is premium
if (Expr::expr(new IsPremium())->evaluate($customer)) {
    // ...
}

// Check if a customer is premium (PHP 7)
if (new IsPremium()->evaluate($customer)) {
    // ...
}

// Get premium customers with bookings
$customers = $repo->findCustomers(Expr::andX([
    new IsPremium(),
    new HasPreviousBookings(),
]));
```

The following sections describe the core expressions in detail.

Expressions
-----------

The [`Expr`] class is able to create the following expressions:

Method                      | Description
--------------------------- | --------------------------------------------------------
`null()`                    | Check that a value is `null`
`notNull()`                 | Check that a value is not `null`
`isEmpty()`                 | Check that a value is empty (using `empty()`)
`notEmpty()`                | Check that a value is not empty (using `empty()`)
`isInstanceOf($className)`  | Check that a value is instance of a class (using `instanceof`)
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
`contains($string)`         | Check that a value contains a given string
`matches($regExp)`          | Check that a value matches a regular expression
`in($values)`               | Check that a value occurs in a list of values
`keyExists($key)`           | Check that a key exists in a value
`keyNotExists($key)`        | Check that a key does not exist in a value
`true()`                    | Always `true` (tautology)
`false()`                   | Always `false` (contradiction)

Selectors
---------

With composite values like arrays or objects, you often want to match only a
part of that value (like an array key or the result of a getter) against an
expression. You can select the evaluated parts with a *selector*.

When you evaluate arrays, use the `key()` selector to match the value of an
array key:

```php
$expr = Expr::key('age', Expr::greaterThan(10));

$expr->evaluate(['age' => 12]);
// => true
```

Each selector method accepts the expression as last argument that should be
evaluated for the selected value.

When evaluating objects, use `property()` and `method()` to evaluate the values
of properties and the results of method calls:

```php
$expr = Expr::property('age', Expr::greaterThan(10));

$expr->evaluate(new Person(12));
// => true

$expr = Expr::method('getAge', Expr::greaterThan(10));

$expr->evaluate(new Person(12));
// => true
```

You can nest selectors to evaluate expressions for nested objects or arrays:

```php
$expr = Expr::atLeastOne(Expr::method('getAge', Expr::greaterThan(10)));

$expr->evaluate([new Person(12), new Person(9)]);
// => true
```

The `method()` selector also accepts arguments that will be passed to the 
method. Pass the arguments before the evaluated expression:

```php
$expr = Expr::method('getParameter', 'age', Expr::greaterThan(10));

$expr->evaluate([new Person(12), new Person(9)]);
// => true
```

The following table lists all available selectors:

Method                      | Description
--------------------------- | -------------------------------------------------------------------------------
`key($key, $expr)`          | Evaluate an expression for a key of an array
`method($name, $expr)`      | Evaluate an expression for the result of a method call
`property($name, $expr)`    | Evaluate an expression for the value of a property
`atLeast($count, $expr)`    | Check that an expression matches for at least `$count` entries of a traversable
`atMost($count, $expr)`     | Check that an expression matches for at most `$count` entries of a traversable
`exactly($count, $expr)`    | Check that an expression matches for exactly `$count` entries of a traversable
`all($expr)`                | Check that an expression matches for all entries of a traversable
`count($expr)`              | Check that an expression matches for the count of a collection

Logical Operators
-----------------

You can negate an expression with `not()`:

```php
$expr = Expr::not(Expr::method('getFirstName', Expr::startsWith('Tho')));
```

You can connect multiple expressions with "and" using the `and*()` methods:

```php
$expr = Expr::method('getFirstName', Expr::startsWith('Tho'))
    ->andMethod('getAge', Expr::greaterThan(35));
```

The same is possible for the "or" operator:

```php
$expr = Expr::method('getFirstName', Expr::startsWith('Tho'))
    ->orMethod('getAge', Expr::greaterThan(35));
```

You can use and/or inside selectors:

```php
$expr = Expr::method('getAge', Expr::greaterThan(35)->orLessThan(20));
```

If you want to mix and match "and" and "or" operators, use `andX()` and `orX()`
to add embedded expressions:

```php
$expr = Expr::method('getFirstName', Expr::startsWith('Tho'))
    ->andX(
        Expr::method('getAge', Expr::lessThan(14))
            ->orMethod('isReduced', Expr::same(true))
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
$c1 = Expr::notNull()->andSame(35);
$c2 = Expr::same(35)->andNotNull();

$c1 == $c2;
// => false

$c1->equivalentTo($c2);
// => true

// Also logically equivalent
$c1 = Expr::same(35);
$c2 = Expr::oneOf([35]);

$c1 == $c2;
// => false

$c1->equivalentTo($c2);
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
[Specification Pattern]: http://www.martinfowler.com/apsupp/spec.pdf
[rulerz]: https://github.com/K-Phoen/rulerz
