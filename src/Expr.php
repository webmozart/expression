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

use Webmozart\Expression\Comparison\EndsWith;
use Webmozart\Expression\Comparison\Equals;
use Webmozart\Expression\Comparison\GreaterThan;
use Webmozart\Expression\Comparison\GreaterThanEqual;
use Webmozart\Expression\Comparison\IsEmpty;
use Webmozart\Expression\Comparison\KeyExists;
use Webmozart\Expression\Comparison\KeyNotExists;
use Webmozart\Expression\Comparison\LessThan;
use Webmozart\Expression\Comparison\LessThanEqual;
use Webmozart\Expression\Comparison\Matches;
use Webmozart\Expression\Comparison\NotEmpty;
use Webmozart\Expression\Comparison\NotEquals;
use Webmozart\Expression\Comparison\NotSame;
use Webmozart\Expression\Comparison\In;
use Webmozart\Expression\Comparison\Same;
use Webmozart\Expression\Comparison\StartsWith;
use Webmozart\Expression\Logic\Invalid;
use Webmozart\Expression\Logic\Not;
use Webmozart\Expression\Logic\Valid;
use Webmozart\Expression\Selector\All;
use Webmozart\Expression\Selector\AtLeast;
use Webmozart\Expression\Selector\AtMost;
use Webmozart\Expression\Selector\Count;
use Webmozart\Expression\Selector\Exactly;
use Webmozart\Expression\Selector\Key;

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
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class Expr
{
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
     * Always true (tautology).
     *
     * @return Valid The created expression.
     */
    public static function valid()
    {
        return new Valid();
    }

    /**
     * Always false (contradiction).
     *
     * @return Valid The created expression.
     */
    public static function invalid()
    {
        return new Invalid();
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
     * @param string|int|null $key The array key to apply the expression to.
     *                             Equivalent to calling {@link key()}.
     *
     * @return Same|Key The created expression.
     */
    public static function null($key = null)
    {
        if (null !== $key) {
            return self::key($key, new Same(null));
        }

        return new Same(null);
    }

    /**
     * Check that a value is not null.
     *
     * @param string|int|null $key The array key to apply the expression to.
     *                             Equivalent to calling {@link key()}.
     *
     * @return NotSame|Key The created expression.
     */
    public static function notNull($key = null)
    {
        if (null !== $key) {
            return self::key($key, new NotSame(null));
        }

        return new NotSame(null);
    }

    /**
     * Check that a value is empty.
     *
     * @param string|int|null $key The array key to apply the expression to.
     *                             Equivalent to calling {@link key()}.
     *
     * @return IsEmpty|Key The created expression.
     */
    public static function isEmpty($key = null)
    {
        if (null !== $key) {
            return self::key($key, new IsEmpty());
        }

        return new IsEmpty();
    }

    /**
     * Check that a value is not empty.
     *
     * @param string|int|null $key The array key to apply the expression to.
     *                             Equivalent to calling {@link key()}.
     *
     * @return NotEmpty|Key The created expression.
     */
    public static function notEmpty($key = null)
    {
        if (null !== $key) {
            return self::key($key, new NotEmpty());
        }

        return new NotEmpty();
    }

    /**
     * Check that a value is true.
     *
     * @param string|int|null $key The array key to apply the expression to.
     *                             Equivalent to calling {@link key()}.
     *
     * @return Same|Key The created expression.
     */
    public static function true($key = null)
    {
        if (null !== $key) {
            return self::key($key, new Same(true));
        }

        return new Same(true);
    }

    /**
     * Check that a value is false.
     *
     * @param string|int|null $key The array key to apply the expression to.
     *                             Equivalent to calling {@link key()}.
     *
     * @return Same|Key The created expression.
     */
    public static function false($key = null)
    {
        if (null !== $key) {
            return self::key($key, new Same(false));
        }

        return new Same(false);
    }

    /**
     * Check that a value equals another value.
     *
     * @param mixed           $value The compared value.
     * @param string|int|null $key   The array key to apply the expression to.
     *                               Equivalent to calling {@link key()}.
     *
     * @return Equals|Key The created expression.
     */
    public static function equals($value, $key = null)
    {
        if (null !== $key) {
            return self::key($key, new Equals($value));
        }

        return new Equals($value);
    }

    /**
     * Check that a value does not equal another value.
     *
     * @param mixed           $value The compared value.
     * @param string|int|null $key   The array key to apply the expression to.
     *                               Equivalent to calling {@link key()}.
     *
     * @return NotEquals|Key The created expression.
     */
    public static function notEquals($value, $key = null)
    {
        if (null !== $key) {
            return self::key($key, new NotEquals($value));
        }

        return new NotEquals($value);
    }

    /**
     * Check that a value is identical to another value.
     *
     * @param mixed           $value The compared value.
     * @param string|int|null $key   The array key to apply the expression to.
     *                               Equivalent to calling {@link key()}.
     *
     * @return Same|Key The created expression.
     */
    public static function same($value, $key = null)
    {
        if (null !== $key) {
            return self::key($key, new Same($value));
        }

        return new Same($value);
    }

    /**
     * Check that a value is not identical to another value.
     *
     * @param mixed           $value The compared value.
     * @param string|int|null $key   The array key to apply the expression to.
     *                               Equivalent to calling {@link key()}.
     *
     * @return NotSame|Key The created expression.
     */
    public static function notSame($value, $key = null)
    {
        if (null !== $key) {
            return self::key($key, new NotSame($value));
        }

        return new NotSame($value);
    }

    /**
     * Check that a value is greater than another value.
     *
     * @param mixed           $value The compared value.
     * @param string|int|null $key   The array key to apply the expression to.
     *                               Equivalent to calling {@link key()}.
     *
     * @return GreaterThan|Key The created expression.
     */
    public static function greaterThan($value, $key = null)
    {
        if (null !== $key) {
            return self::key($key, new GreaterThan($value));
        }

        return new GreaterThan($value);
    }

    /**
     * Check that a value is greater than or equal to another value.
     *
     * @param mixed           $value The compared value.
     * @param string|int|null $key   The array key to apply the expression to.
     *                               Equivalent to calling {@link key()}.
     *
     * @return GreaterThanEqual|Key The created expression.
     */
    public static function greaterThanEqual($value, $key = null)
    {
        if (null !== $key) {
            return self::key($key, new GreaterThanEqual($value));
        }

        return new GreaterThanEqual($value);
    }

    /**
     * Check that a value is less than another value.
     *
     * @param mixed           $value The compared value.
     * @param string|int|null $key   The array key to apply the expression to.
     *                               Equivalent to calling {@link key()}.
     *
     * @return LessThan|Key The created expression.
     */
    public static function lessThan($value, $key = null)
    {
        if (null !== $key) {
            return self::key($key, new LessThan($value));
        }

        return new LessThan($value);
    }

    /**
     * Check that a value is less than or equal to another value.
     *
     * @param mixed           $value The compared value.
     * @param string|int|null $key   The array key to apply the expression to.
     *                               Equivalent to calling {@link key()}.
     *
     * @return LessThanEqual|Key The created expression.
     */
    public static function lessThanEqual($value, $key = null)
    {
        if (null !== $key) {
            return self::key($key, new LessThanEqual($value));
        }

        return new LessThanEqual($value);
    }

    /**
     * Check that a value occurs in a list of values.
     *
     * @param array           $values The compared values.
     * @param string|int|null $key    The array key to apply the expression to.
     *                                Equivalent to calling {@link key()}.
     *
     * @return In|Key The created expression.
     */
    public static function in(array $values, $key = null)
    {
        if (null !== $key) {
            return self::key($key, new In($values));
        }

        return new In($values);
    }

    /**
     * Check that a value matches a regular expression.
     *
     * @param string          $regExp The regular expression.
     * @param string|int|null $key    The array key to apply the expression to.
     *                                Equivalent to calling {@link key()}.
     *
     * @return Matches|Key The created expression.
     */
    public static function matches($regExp, $key = null)
    {
        if (null !== $key) {
            return self::key($key, new Matches($regExp));
        }

        return new Matches($regExp);
    }

    /**
     * Check that a value starts with a given string.
     *
     * @param string          $prefix The prefix string.
     * @param string|int|null $key    The array key to apply the expression to.
     *                                Equivalent to calling {@link key()}.
     *
     * @return StartsWith|Key The created expression.
     */
    public static function startsWith($prefix, $key = null)
    {
        if (null !== $key) {
            return self::key($key, new StartsWith($prefix));
        }

        return new StartsWith($prefix);
    }

    /**
     * Check that a value ends with a given string.
     *
     * @param string          $suffix The suffix string.
     * @param string|int|null $key    The array key to apply the expression to.
     *                                Equivalent to calling {@link key()}.
     *
     * @return EndsWith|Key The created expression.
     */
    public static function endsWith($suffix, $key = null)
    {
        if (null !== $key) {
            return self::key($key, new EndsWith($suffix));
        }

        return new EndsWith($suffix);
    }

    /**
     * Check that a value key exists.
     *
     * @param string          $keyName The key name.
     * @param string|int|null $key     The array key to apply the expression to.
     *                                 Equivalent to calling {@link key()}.
     *
     * @return KeyExists|Key The created expression.
     */
    public static function keyExists($keyName, $key = null)
    {
        if (null !== $key) {
            return self::key($key, new KeyExists($keyName));
        }

        return new KeyExists($keyName);
    }

    /**
     * Check that a value key does not exist.
     *
     * @param string          $keyName The key name.
     * @param string|int|null $key     The array key to apply the expression to.
     *                                 Equivalent to calling {@link key()}.
     *
     * @return KeyNotExists|Key The created expression.
     */
    public static function keyNotExists($keyName, $key = null)
    {
        if (null !== $key) {
            return self::key($key, new KeyNotExists($keyName));
        }

        return new KeyNotExists($keyName);
    }

    /**
     * This class cannot be instantiated.
     */
    private function __construct()
    {
    }
}
