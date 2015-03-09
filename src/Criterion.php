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

use Webmozart\Criteria\Atom\EndsWith;
use Webmozart\Criteria\Atom\Equals;
use Webmozart\Criteria\Atom\False;
use Webmozart\Criteria\Atom\GreaterThan;
use Webmozart\Criteria\Atom\GreaterThanEqual;
use Webmozart\Criteria\Atom\IsEmpty;
use Webmozart\Criteria\Atom\LessThan;
use Webmozart\Criteria\Atom\LessThanEqual;
use Webmozart\Criteria\Atom\Matches;
use Webmozart\Criteria\Atom\NotEmpty;
use Webmozart\Criteria\Atom\NotEquals;
use Webmozart\Criteria\Atom\NotNull;
use Webmozart\Criteria\Atom\NotSame;
use Webmozart\Criteria\Atom\Null;
use Webmozart\Criteria\Atom\OneOf;
use Webmozart\Criteria\Atom\Same;
use Webmozart\Criteria\Atom\StartsWith;
use Webmozart\Criteria\Atom\True;
use Webmozart\Criteria\Literal\Not;

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
     * @return Null The created criterion.
     */
    public static function null($field)
    {
        return new Null($field);
    }

    /**
     * Check that a field is not null.
     *
     * @param string $field The field name.
     *
     * @return NotNull The created criterion.
     */
    public static function notNull($field)
    {
        return new NotNull($field);
    }

    /**
     * Check that a field is empty.
     *
     * @param string $field The field name.
     *
     * @return Null The created criterion.
     */
    public static function isEmpty($field)
    {
        return new IsEmpty($field);
    }

    /**
     * Check that a field is not empty.
     *
     * @param string $field The field name.
     *
     * @return NotNull The created criterion.
     */
    public static function notEmpty($field)
    {
        return new NotEmpty($field);
    }

    /**
     * Check that a field is true.
     *
     * @param string $field  The field name.
     * @param bool   $strict Whether to use strict comparison.
     *
     * @return Null The created criterion.
     */
    public static function true($field, $strict = true)
    {
        return new True($field, $strict);
    }

    /**
     * Check that a field is false.
     *
     * @param string $field  The field name.
     * @param bool   $strict Whether to use strict comparison.
     *
     * @return Null The created criterion.
     */
    public static function false($field, $strict = true)
    {
        return new False($field, $strict);
    }

    /**
     * Check that a field equals a value.
     *
     * @param string $field The field name.
     * @param mixed  $value The compared value.
     *
     * @return Equals The created criterion.
     */
    public static function equals($field, $value)
    {
        return new Equals($field, $value);
    }

    /**
     * Check that a field does not equal a value.
     *
     * @param string $field The field name.
     * @param mixed  $value The compared value.
     *
     * @return NotEquals The created criterion.
     */
    public static function notEquals($field, $value)
    {
        return new NotEquals($field, $value);
    }

    /**
     * Check that a field is identical to a value.
     *
     * @param string $field The field name.
     * @param mixed  $value The compared value.
     *
     * @return Same The created criterion.
     */
    public static function same($field, $value)
    {
        return new Same($field, $value);
    }

    /**
     * Check that a field is not identical to a value.
     *
     * @param string $field The field name.
     * @param mixed  $value The compared value.
     *
     * @return NotSame The created criterion.
     */
    public static function notSame($field, $value)
    {
        return new NotSame($field, $value);
    }

    /**
     * Check that a field is greater than a value.
     *
     * @param string $field The field name.
     * @param mixed  $value The compared value.
     *
     * @return GreaterThan The created criterion.
     */
    public static function greaterThan($field, $value)
    {
        return new GreaterThan($field, $value);
    }

    /**
     * Check that a field is greater than or equal to a value.
     *
     * @param string $field The field name.
     * @param mixed  $value The compared value.
     *
     * @return GreaterThanEqual The created criterion.
     */
    public static function greaterThanEqual($field, $value)
    {
        return new GreaterThanEqual($field, $value);
    }

    /**
     * Check that a field is less than a value.
     *
     * @param string $field The field name.
     * @param mixed  $value The compared value.
     *
     * @return LessThan The created criterion.
     */
    public static function lessThan($field, $value)
    {
        return new LessThan($field, $value);
    }

    /**
     * Check that a field is less than or equal to a value.
     *
     * @param string $field The field name.
     * @param mixed  $value The compared value.
     *
     * @return LessThanEqual The created criterion.
     */
    public static function lessThanEqual($field, $value)
    {
        return new LessThanEqual($field, $value);
    }

    /**
     * Check that a field contains one of a set of values.
     *
     * @param string $field  The field name.
     * @param array  $values The compared values.
     * @param bool   $strict Whether to do strict comparison.
     *
     * @return OneOf The created criterion.
     */
    public static function oneOf($field, array $values, $strict = true)
    {
        return new OneOf($field, $values, $strict);
    }

    /**
     * Check that a field matches a regular expression.
     *
     * @param string $field  The field name.
     * @param string $regExp The regular expression.
     *
     * @return Matches The created criterion.
     */
    public static function matches($field, $regExp)
    {
        return new Matches($field, $regExp);
    }

    /**
     * Check that a field starts with a given string.
     *
     * @param string $field  The field name.
     * @param string $prefix The prefix string.
     *
     * @return StartsWith The created criterion.
     */
    public static function startsWith($field, $prefix)
    {
        return new StartsWith($field, $prefix);
    }

    /**
     * Check that a field ends with a given string.
     *
     * @param string $field  The field name.
     * @param string $suffix The suffix string.
     *
     * @return EndsWith The created criterion.
     */
    public static function endsWith($field, $suffix)
    {
        return new EndsWith($field, $suffix);
    }

    /**
     * This class cannot be instantiated.
     */
    private function __construct()
    {
    }
}
