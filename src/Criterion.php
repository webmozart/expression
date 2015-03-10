<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria;

use Webmozart\Criteria\Comparison\EndsWith;
use Webmozart\Criteria\Comparison\Equals;
use Webmozart\Criteria\Comparison\False;
use Webmozart\Criteria\Comparison\GreaterThan;
use Webmozart\Criteria\Comparison\GreaterThanEqual;
use Webmozart\Criteria\Comparison\IsEmpty;
use Webmozart\Criteria\Comparison\LessThan;
use Webmozart\Criteria\Comparison\LessThanEqual;
use Webmozart\Criteria\Comparison\Matches;
use Webmozart\Criteria\Comparison\NotEmpty;
use Webmozart\Criteria\Comparison\NotEquals;
use Webmozart\Criteria\Comparison\NotNull;
use Webmozart\Criteria\Comparison\NotSame;
use Webmozart\Criteria\Comparison\Null;
use Webmozart\Criteria\Comparison\OneOf;
use Webmozart\Criteria\Comparison\Same;
use Webmozart\Criteria\Comparison\StartsWith;
use Webmozart\Criteria\Comparison\True;
use Webmozart\Criteria\Key\Key;
use Webmozart\Criteria\Key\KeyExists;
use Webmozart\Criteria\Key\KeyNotExists;
use Webmozart\Criteria\Logic\Not;

/**
 * Factory for {@link Criteria} instances.
 *
 * Use this class to build criteria for a set of fields:
 *
 * ```php
 * $criteria = Criterion::greaterThan('age', 20)
 *     ->andStartsWith('name', 'Thomas');
 * ```
 *
 * You can evaluate the criteria with a set of values using
 * {@link Criteria::match()}:
 *
 * ```php
 * $values = array(
 *     'name' => 'Thomas Edison',
 *     'age' => 35,
 * );
 *
 * if ($criteria->match($values)) {
 *     // do something...
 * }
 * ```
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class Criterion
{
    /**
     * Check that some criteria do not match.
     *
     * @param Criteria $criteria The negated criteria.
     *
     * @return Not The created negation.
     */
    public static function not(Criteria $criteria)
    {
        return new Not($criteria);
    }

    /**
     * Check that a field is null.
     *
     * @param string $field The field name.
     *
     * @return Key The created criterion.
     */
    public static function null($field)
    {
        return new Key($field, new Null());
    }

    /**
     * Check that a field is not null.
     *
     * @param string $field The field name.
     *
     * @return Key The created criterion.
     */
    public static function notNull($field)
    {
        return new Key($field, new NotNull());
    }

    /**
     * Check that a field is empty.
     *
     * @param string $field The field name.
     *
     * @return Key The created criterion.
     */
    public static function isEmpty($field)
    {
        return new Key($field, new IsEmpty());
    }

    /**
     * Check that a field is not empty.
     *
     * @param string $field The field name.
     *
     * @return Key The created criterion.
     */
    public static function notEmpty($field)
    {
        return new Key($field, new NotEmpty());
    }

    /**
     * Check that a field is true.
     *
     * @param string $field  The field name.
     * @param bool   $strict Whether to use strict comparison.
     *
     * @return Key The created criterion.
     */
    public static function true($field, $strict = true)
    {
        return new Key($field, new True($strict));
    }

    /**
     * Check that a field is false.
     *
     * @param string $field  The field name.
     * @param bool   $strict Whether to use strict comparison.
     *
     * @return Key The created criterion.
     */
    public static function false($field, $strict = true)
    {
        return new Key($field, new False($strict));
    }

    /**
     * Check that a field equals a value.
     *
     * @param string $field The field name.
     * @param mixed  $value The compared value.
     *
     * @return Key The created criterion.
     */
    public static function equals($field, $value)
    {
        return new Key($field, new Equals($value));
    }

    /**
     * Check that a field does not equal a value.
     *
     * @param string $field The field name.
     * @param mixed  $value The compared value.
     *
     * @return Key The created criterion.
     */
    public static function notEquals($field, $value)
    {
        return new Key($field, new NotEquals($value));
    }

    /**
     * Check that a field is identical to a value.
     *
     * @param string $field The field name.
     * @param mixed  $value The compared value.
     *
     * @return Key The created criterion.
     */
    public static function same($field, $value)
    {
        return new Key($field, new Same($value));
    }

    /**
     * Check that a field is not identical to a value.
     *
     * @param string $field The field name.
     * @param mixed  $value The compared value.
     *
     * @return Key The created criterion.
     */
    public static function notSame($field, $value)
    {
        return new Key($field, new NotSame($value));
    }

    /**
     * Check that a field is greater than a value.
     *
     * @param string $field The field name.
     * @param mixed  $value The compared value.
     *
     * @return Key The created criterion.
     */
    public static function greaterThan($field, $value)
    {
        return new Key($field, new GreaterThan($value));
    }

    /**
     * Check that a field is greater than or equal to a value.
     *
     * @param string $field The field name.
     * @param mixed  $value The compared value.
     *
     * @return Key The created criterion.
     */
    public static function greaterThanEqual($field, $value)
    {
        return new Key($field, new GreaterThanEqual($value));
    }

    /**
     * Check that a field is less than a value.
     *
     * @param string $field The field name.
     * @param mixed  $value The compared value.
     *
     * @return Key The created criterion.
     */
    public static function lessThan($field, $value)
    {
        return new Key($field, new LessThan($value));
    }

    /**
     * Check that a field is less than or equal to a value.
     *
     * @param string $field The field name.
     * @param mixed  $value The compared value.
     *
     * @return Key The created criterion.
     */
    public static function lessThanEqual($field, $value)
    {
        return new Key($field, new LessThanEqual($value));
    }

    /**
     * Check that a field contains one of a set of values.
     *
     * @param string $field  The field name.
     * @param array  $values The compared values.
     * @param bool   $strict Whether to do strict comparison.
     *
     * @return Key The created criterion.
     */
    public static function oneOf($field, array $values, $strict = true)
    {
        return new Key($field, new OneOf($values, $strict));
    }

    /**
     * Check that a field matches a regular expression.
     *
     * @param string $field  The field name.
     * @param string $regExp The regular expression.
     *
     * @return Key The created criterion.
     */
    public static function matches($field, $regExp)
    {
        return new Key($field, new Matches($regExp));
    }

    /**
     * Check that a field starts with a given string.
     *
     * @param string $field  The field name.
     * @param string $prefix The prefix string.
     *
     * @return Key The created criterion.
     */
    public static function startsWith($field, $prefix)
    {
        return new Key($field, new StartsWith($prefix));
    }

    /**
     * Check that a field ends with a given string.
     *
     * @param string $field  The field name.
     * @param string $suffix The suffix string.
     *
     * @return Key The created criterion.
     */
    public static function endsWith($field, $suffix)
    {
        return new Key($field, new EndsWith($suffix));
    }

    /**
     * Check that a field key satisfies some criteria.
     *
     * @param string   $field    The field name.
     * @param string   $key      The key name.
     * @param Criteria $criteria The criteria.
     *
     * @return Key The created criterion.
     */
    public static function key($field, $key, Criteria $criteria)
    {
        return new Key($field, new Key($key, $criteria));
    }

    /**
     * Check that a field key exists.
     *
     * @param string $field The field name.
     * @param string $key   The key name.
     *
     * @return Key The created criterion.
     */
    public static function keyExists($field, $key)
    {
        return new Key($field, new KeyExists($key));
    }

    /**
     * Check that a field key does not exist.
     *
     * @param string $field The field name.
     * @param string $key   The key name.
     *
     * @return Key The created criterion.
     */
    public static function keyNotExists($field, $key)
    {
        return new Key($field, new KeyNotExists($key));
    }

    /**
     * Check that a field key is null.
     *
     * @param string $field The field name.
     * @param string $key   The key name.
     *
     * @return Key The created criterion.
     */
    public static function keyNull($field, $key)
    {
        return new Key($field, new Key($key, new Null()));
    }

    /**
     * Check that a field key is not null.
     *
     * @param string $field The field name.
     * @param string $key   The key name.
     *
     * @return Key The created criterion.
     */
    public static function keyNotNull($field, $key)
    {
        return new Key($field, new Key($key, new NotNull()));
    }

    /**
     * Check that a field key is empty.
     *
     * @param string $field The field name.
     * @param string $key   The key name.
     *
     * @return Key The created criterion.
     */
    public static function keyEmpty($field, $key)
    {
        return new Key($field, new Key($key, new IsEmpty()));
    }

    /**
     * Check that a field key is not empty.
     *
     * @param string $field The field name.
     * @param string $key   The key name.
     *
     * @return Key The created criterion.
     */
    public static function keyNotEmpty($field, $key)
    {
        return new Key($field, new Key($key, new NotEmpty()));
    }

    /**
     * Check that a field key is true.
     *
     * @param string $field  The field name.
     * @param string $key    The key name.
     * @param bool   $strict Whether to use strict comparison.
     *
     * @return Key The created criterion.
     */
    public static function keyTrue($field, $key, $strict = true)
    {
        return new Key($field, new Key($key, new True($strict)));
    }

    /**
     * Check that a field key is false.
     *
     * @param string $field  The field name.
     * @param string $key    The key name.
     * @param bool   $strict Whether to use strict comparison.
     *
     * @return Key The created criterion.
     */
    public static function keyFalse($field, $key, $strict = true)
    {
        return new Key($field, new Key($key, new False($strict)));
    }

    /**
     * Check that a field key equals a value.
     *
     * @param string $field The field name.
     * @param string $key   The key name.
     * @param mixed  $value The compared value.
     *
     * @return Key The created criterion.
     */
    public static function keyEquals($field, $key, $value)
    {
        return new Key($field, new Key($key, new Equals($value)));
    }

    /**
     * Check that a field key does not equal a value.
     *
     * @param string $field The field name.
     * @param string $key   The key name.
     * @param mixed  $value The compared value.
     *
     * @return Key The created criterion.
     */
    public static function keyNotEquals($field, $key, $value)
    {
        return new Key($field, new Key($key, new NotEquals($value)));
    }

    /**
     * Check that a field key is identical to a value.
     *
     * @param string $field The field name.
     * @param string $key   The key name.
     * @param mixed  $value The compared value.
     *
     * @return Key The created criterion.
     */
    public static function keySame($field, $key, $value)
    {
        return new Key($field, new Key($key, new Same($value)));
    }

    /**
     * Check that a field key is not identical to a value.
     *
     * @param string $field The field name.
     * @param string $key   The key name.
     * @param mixed  $value The compared value.
     *
     * @return Key The created criterion.
     */
    public static function keyNotSame($field, $key, $value)
    {
        return new Key($field, new Key($key, new NotSame($value)));
    }

    /**
     * Check that a field key is greater than a value.
     *
     * @param string $field The field name.
     * @param string $key   The key name.
     * @param mixed  $value The compared value.
     *
     * @return Key The created criterion.
     */
    public static function keyGreaterThan($field, $key, $value)
    {
        return new Key($field, new Key($key, new GreaterThan($value)));
    }

    /**
     * Check that a field key is greater than or equal to a value.
     *
     * @param string $field The field name.
     * @param string $key   The key name.
     * @param mixed  $value The compared value.
     *
     * @return Key The created criterion.
     */
    public static function keyGreaterThanEqual($field, $key, $value)
    {
        return new Key($field, new Key($key, new GreaterThanEqual($value)));
    }

    /**
     * Check that a field key is less than a value.
     *
     * @param string $field The field name.
     * @param string $key   The key name.
     * @param mixed  $value The compared value.
     *
     * @return Key The created criterion.
     */
    public static function keyLessThan($field, $key, $value)
    {
        return new Key($field, new Key($key, new LessThan($value)));
    }

    /**
     * Check that a field key is less than or equal to a value.
     *
     * @param string $field The field name.
     * @param string $key   The key name.
     * @param mixed  $value The compared value.
     *
     * @return Key The created criterion.
     */
    public static function keyLessThanEqual($field, $key, $value)
    {
        return new Key($field, new Key($key, new LessThanEqual($value)));
    }

    /**
     * Check that a field key contains one of a set of values.
     *
     * @param string $field  The field name.
     * @param string $key    The key name.
     * @param array  $values The compared values.
     * @param bool   $strict Whether to do strict comparison.
     *
     * @return Key The created criterion.
     */
    public static function keyOneOf($field, $key, array $values, $strict = true)
    {
        return new Key($field, new Key($key, new OneOf($values, $strict)));
    }

    /**
     * Check that a field key matches a regular expression.
     *
     * @param string $field  The field name.
     * @param string $key    The key name.
     * @param string $regExp The regular expression.
     *
     * @return Key The created criterion.
     */
    public static function keyMatches($field, $key, $regExp)
    {
        return new Key($field, new Key($key, new Matches($regExp)));
    }

    /**
     * Check that a field key starts with a given string.
     *
     * @param string $field  The field name.
     * @param string $key    The key name.
     * @param string $prefix The prefix string.
     *
     * @return Key The created criterion.
     */
    public static function keyStartsWith($field, $key, $prefix)
    {
        return new Key($field, new Key($key, new StartsWith($prefix)));
    }

    /**
     * Check that a field key ends with a given string.
     *
     * @param string $field  The field name.
     * @param string $key    The key name.
     * @param string $suffix The suffix string.
     *
     * @return Key The created criterion.
     */
    public static function keyEndsWith($field, $key, $suffix)
    {
        return new Key($field, new Key($key, new EndsWith($suffix)));
    }

    /**
     * This class cannot be instantiated.
     */
    private function __construct()
    {
    }
}
