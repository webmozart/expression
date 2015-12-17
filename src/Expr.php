<?php

/*
 * This file is part of the webmozart/expression package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression;

use ArrayAccess;
use InvalidArgumentException;
use Traversable;
use Webmozart\Expression\Constraint\Contains;
use Webmozart\Expression\Constraint\EndsWith;
use Webmozart\Expression\Constraint\Equals;
use Webmozart\Expression\Constraint\GreaterThan;
use Webmozart\Expression\Constraint\GreaterThanEqual;
use Webmozart\Expression\Constraint\In;
use Webmozart\Expression\Constraint\IsEmpty;
use Webmozart\Expression\Constraint\IsInstanceOf;
use Webmozart\Expression\Constraint\KeyExists;
use Webmozart\Expression\Constraint\KeyNotExists;
use Webmozart\Expression\Constraint\LessThan;
use Webmozart\Expression\Constraint\LessThanEqual;
use Webmozart\Expression\Constraint\Matches;
use Webmozart\Expression\Constraint\NotEquals;
use Webmozart\Expression\Constraint\NotSame;
use Webmozart\Expression\Constraint\Same;
use Webmozart\Expression\Constraint\StartsWith;
use Webmozart\Expression\Logic\AlwaysFalse;
use Webmozart\Expression\Logic\AlwaysTrue;
use Webmozart\Expression\Logic\AndX;
use Webmozart\Expression\Logic\Not;
use Webmozart\Expression\Logic\OrX;
use Webmozart\Expression\Selector\All;
use Webmozart\Expression\Selector\AtLeast;
use Webmozart\Expression\Selector\AtMost;
use Webmozart\Expression\Selector\Count;
use Webmozart\Expression\Selector\Exactly;
use Webmozart\Expression\Selector\Key;
use Webmozart\Expression\Selector\Method;
use Webmozart\Expression\Selector\Property;

/**
 * Factory for {@link Expression} instances.
 *
 * Use this class to build expressions:ons:
 *
 * ```php
 * $expr = Expr::greaterThan(20)->orLessThan(10);
 * ```
 *
 * You can evaluate the expression with another value {@link Expression::evaluate()}:
 *
 * ```php
 * if ($expr->evaluate($value)) {
 *     // do something...
 * }
 * ```
 *
 * You can also evaluate expressions by arrays by passing the array keys as
 * second argument:
 *
 * ```php
 * $expr = Expr::greaterThan(20, 'age')
 *     ->andStartsWith('Thomas', 'name');
 *
 * $values = array(
 *     'age' => 35,
 *     'name' => 'Thomas Edison',
 * );
 *
 * if ($expr->evaluate($values)) {
 *     // do something...
 * }
 * ```
 *
 * @since  1.0
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class Expr
{
    /**
     * Filter a collection for entries matching the expression.
     *
     * @param array|ArrayAccess|Traversable $collection An array or an object
     *                                                  implementing Traversable
     *                                                  and ArrayAccess.
     * @param Expression                    $expr       The expression to
     *                                                  evaluate for each entry.
     *
     * @return array|ArrayAccess|Traversable The filtered collection.
     */
    public static function filter($collection, Expression $expr)
    {
        if (is_array($collection)) {
            return array_filter($collection, array($expr, 'evaluate'));
        }

        if (!($collection instanceof Traversable && $collection instanceof ArrayAccess)) {
            throw new InvalidArgumentException(sprintf(
                'Expected an array or an instance of Traversable and ArrayAccess. Got: %s',
                is_object($collection) ? get_class($collection) : gettype($collection)
            ));
        }

        $clone = clone $collection;

        foreach ($collection as $key => $value) {
            if (!$expr->evaluate($value)) {
                unset($clone[$key]);
            }
        }

        return $clone;
    }

    /**
     * Returns the expression.
     *
     * Facilitates usage of expressions on PHP < 7.
     *
     * @param Expression $expr An expression.
     *
     * @return Expression The expression.
     */
    public static function expr(Expression $expr)
    {
        return $expr;
    }

    /**
     * Negate an expression.
     *
     * @param Expression $expr The negated expression.
     *
     * @return Not The created negation.
     */
    public static function not(Expression $expr)
    {
        return new Not($expr);
    }

    /**
     * Create a conjunction.
     *
     * @param Expression[] $conjuncts The conjuncts.
     *
     * @return AndX The created conjunction.
     */
    public static function andX(array $conjuncts)
    {
        return new AndX($conjuncts);
    }

    /**
     * Create a disjunction.
     *
     * @param Expression[] $disjuncts The disjuncts.
     *
     * @return OrX The created disjunction.
     */
    public static function orX(array $disjuncts)
    {
        return new OrX($disjuncts);
    }

    /**
     * Always true (tautology).
     *
     * @return AlwaysTrue The created expression.
     */
    public static function true()
    {
        return new AlwaysTrue();
    }

    /**
     * Always false (contradiction).
     *
     * @return AlwaysFalse The created expression.
     */
    public static function false()
    {
        return new AlwaysFalse();
    }

    /**
     * Check that the value of an array key matches an expression.
     *
     * @param string|int $key  The array key.
     * @param Expression $expr The evaluated expression.
     *
     * @return Key The created expression.
     */
    public static function key($key, Expression $expr)
    {
        return new Key($key, $expr);
    }

    /**
     * Check that the result of a method call matches an expression.
     *
     * @param string     $methodName The name of the method to call.
     * @param mixed      $args...    The method arguments.
     * @param Expression $expr       The evaluated expression.
     *
     * @return Method The created expression.
     */
    public static function method($methodName, $args)
    {
        $args = func_get_args();
        $methodName = array_shift($args);
        $expr = array_pop($args);

        return new Method($methodName, $args, $expr);
    }

    /**
     * Check that the value of a property matches an expression.
     *
     * @param string     $propertyName The name of the property.
     * @param Expression $expr         The evaluated expression.
     *
     * @return Method The created expression.
     */
    public static function property($propertyName, Expression $expr)
    {
        return new Property($propertyName, $expr);
    }

    /**
     * Check that at least N entries of a traversable value match an expression.
     *
     * @param int        $count The minimum number of entries that need to match.
     * @param Expression $expr  The evaluated expression.
     *
     * @return AtLeast The created expression.
     */
    public static function atLeast($count, Expression $expr)
    {
        return new AtLeast($count, $expr);
    }

    /**
     * Check that at most N entries of a traversable value match an expression.
     *
     * @param int        $count The maximum number of entries that need to match.
     * @param Expression $expr  The evaluated expression.
     *
     * @return AtMost The created expression.
     */
    public static function atMost($count, Expression $expr)
    {
        return new AtMost($count, $expr);
    }

    /**
     * Check that exactly N entries of a traversable value match an expression.
     *
     * @param int        $count The number of entries that need to match.
     * @param Expression $expr  The evaluated expression.
     *
     * @return Exactly The created expression.
     */
    public static function exactly($count, Expression $expr)
    {
        return new Exactly($count, $expr);
    }

    /**
     * Check that all entries of a traversable value match an expression.
     *
     * @param Expression $expr The evaluated expression.
     *
     * @return All The created expression.
     */
    public static function all(Expression $expr)
    {
        return new All($expr);
    }

    /**
     * Check that the count of a collection matches an expression.
     *
     * @param Expression $expr The evaluated expression.
     *
     * @return Count The created expression.
     */
    public static function count(Expression $expr)
    {
        return new Count($expr);
    }

    /**
     * Check that a value is null.
     *
     * @return Same The created expression.
     */
    public static function null()
    {
        return new Same(null);
    }

    /**
     * Check that a value is not null.
     *
     * @return NotSame The created expression.
     */
    public static function notNull()
    {
        return new NotSame(null);
    }

    /**
     * Check that a value is empty.
     *
     * @return IsEmpty The created expression.
     */
    public static function isEmpty()
    {
        return new IsEmpty();
    }

    /**
     * Check that a value is not empty.
     *
     * @return Not The created expression.
     */
    public static function notEmpty()
    {
        return new Not(new IsEmpty());
    }

    /**
     * Check that a value is an instance of a given class.
     *
     * @param string $className The class name.
     *
     * @return IsEmpty The created expression.
     */
    public static function isInstanceOf($className)
    {
        return new IsInstanceOf($className);
    }

    /**
     * Check that a value equals another value.
     *
     * @param mixed $value The compared value.
     *
     * @return Equals The created expression.
     */
    public static function equals($value)
    {
        return new Equals($value);
    }

    /**
     * Check that a value does not equal another value.
     *
     * @param mixed $value The compared value.
     *
     * @return NotEquals The created expression.
     */
    public static function notEquals($value)
    {
        return new NotEquals($value);
    }

    /**
     * Check that a value is identical to another value.
     *
     * @param mixed $value The compared value.
     *
     * @return Same The created expression.
     */
    public static function same($value)
    {
        return new Same($value);
    }

    /**
     * Check that a value is not identical to another value.
     *
     * @param mixed $value The compared value.
     *
     * @return NotSame The created expression.
     */
    public static function notSame($value)
    {
        return new NotSame($value);
    }

    /**
     * Check that a value is greater than another value.
     *
     * @param mixed $value The compared value.
     *
     * @return GreaterThan The created expression.
     */
    public static function greaterThan($value)
    {
        return new GreaterThan($value);
    }

    /**
     * Check that a value is greater than or equal to another value.
     *
     * @param mixed $value The compared value.
     *
     * @return GreaterThanEqual The created expression.
     */
    public static function greaterThanEqual($value)
    {
        return new GreaterThanEqual($value);
    }

    /**
     * Check that a value is less than another value.
     *
     * @param mixed $value The compared value.
     *
     * @return LessThan The created expression.
     */
    public static function lessThan($value)
    {
        return new LessThan($value);
    }

    /**
     * Check that a value is less than or equal to another value.
     *
     * @param mixed $value The compared value.
     *
     * @return LessThanEqual The created expression.
     */
    public static function lessThanEqual($value)
    {
        return new LessThanEqual($value);
    }

    /**
     * Check that a value occurs in a list of values.
     *
     * @param array $values The compared values.
     *
     * @return In The created expression.
     */
    public static function in(array $values)
    {
        return new In($values);
    }

    /**
     * Check that a value matches a regular expression.
     *
     * @param string $regExp The regular expression.
     *
     * @return Matches The created expression.
     */
    public static function matches($regExp)
    {
        return new Matches($regExp);
    }

    /**
     * Check that a value starts with a given string.
     *
     * @param string $prefix The prefix string.
     *
     * @return StartsWith The created expression.
     */
    public static function startsWith($prefix)
    {
        return new StartsWith($prefix);
    }

    /**
     * Check that a value ends with a given string.
     *
     * @param string $suffix The suffix string.
     *
     * @return EndsWith The created expression.
     */
    public static function endsWith($suffix)
    {
        return new EndsWith($suffix);
    }

    /**
     * Check that a value contains a given string.
     *
     * @param string $string The sub-string.
     *
     * @return Contains The created expression.
     */
    public static function contains($string)
    {
        return new Contains($string);
    }

    /**
     * Check that a value key exists.
     *
     * @param string $keyName The key name.
     *
     * @return KeyExists The created expression.
     */
    public static function keyExists($keyName)
    {
        return new KeyExists($keyName);
    }

    /**
     * Check that a value key does not exist.
     *
     * @param string $keyName The key name.
     *
     * @return KeyNotExists The created expression.
     */
    public static function keyNotExists($keyName)
    {
        return new KeyNotExists($keyName);
    }

    /**
     * This class cannot be instantiated.
     */
    private function __construct()
    {
    }
}
