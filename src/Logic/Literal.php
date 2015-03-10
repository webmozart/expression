<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria\Logic;

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
use Webmozart\Criteria\Criteria;
use Webmozart\Criteria\Key\Key;
use Webmozart\Criteria\Key\KeyExists;
use Webmozart\Criteria\Key\KeyNotExists;

/**
 * A logical literal.
 *
 * A literal is any part of a formula that does not contain "and" and "or"
 * operators. In other words, a literal is an {@link Atom} or a negated
 * {@link Atom}.
 *
 * Examples:
 *
 *  * not endsWith(fileName, ".css")
 *  * greaterThan(age, 0)
 *
 * The following examples are *not* literals:
 *
 *  * greaterThan(age, 0) and lessThan(age, 120)
 *  * oneOf(category, ["A", "B", "C]) or null(category)
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
abstract class Literal implements Criteria
{
    /**
     * {@inheritdoc}
     */
    public function equals(Criteria $other)
    {
        return $other == $this;
    }

    public function andX(Criteria $x)
    {
        if ($this->equals($x)) {
            return $this;
        }

        return new Conjunction(array($this, $x));
    }

    public function andNot(Criteria $criteria)
    {
        return $this->andX(new Not($criteria));
    }

    public function andNull($field)
    {
        return $this->andX(new Key($field, new Null()));
    }

    public function andNotNull($field)
    {
        return $this->andX(new Key($field, new NotNull()));
    }

    public function andEmpty($field)
    {
        return $this->andX(new Key($field, new IsEmpty()));
    }

    public function andNotEmpty($field)
    {
        return $this->andX(new Key($field, new NotEmpty()));
    }

    public function andTrue($field, $strict = true)
    {
        return $this->andX(new Key($field, new True($strict)));
    }

    public function andFalse($field, $strict = true)
    {
        return $this->andX(new Key($field, new False($strict)));
    }

    public function andEquals($field, $value)
    {
        return $this->andX(new Key($field, new Equals($value)));
    }

    public function andNotEquals($field, $value)
    {
        return $this->andX(new Key($field, new NotEquals($value)));
    }

    public function andSame($field, $value)
    {
        return $this->andX(new Key($field, new Same($value)));
    }

    public function andNotSame($field, $value)
    {
        return $this->andX(new Key($field, new NotSame($value)));
    }

    public function andGreaterThan($field, $value)
    {
        return $this->andX(new Key($field, new GreaterThan($value)));
    }

    public function andGreaterThanEqual($field, $value)
    {
        return $this->andX(new Key($field, new GreaterThanEqual($value)));
    }

    public function andLessThan($field, $value)
    {
        return $this->andX(new Key($field, new LessThan($value)));
    }

    public function andLessThanEqual($field, $value)
    {
        return $this->andX(new Key($field, new LessThanEqual($value)));
    }

    public function andOneOf($field, array $values, $strict = true)
    {
        return $this->andX(new Key($field, new OneOf($values, $strict)));
    }

    public function andMatches($field, $regExp)
    {
        return $this->andX(new Key($field, new Matches($regExp)));
    }

    public function andStartsWith($field, $prefix)
    {
        return $this->andX(new Key($field, new StartsWith($prefix)));
    }

    public function andEndsWith($field, $suffix)
    {
        return $this->andX(new Key($field, new EndsWith($suffix)));
    }

    public function andKey($field, $key, Criteria $criteria)
    {
        return $this->andX(new Key($field, new Key($key, $criteria)));
    }

    public function andKeyExists($field, $key)
    {
        return $this->andX(new Key($field, new KeyExists($key)));
    }

    public function andKeyNotExists($field, $key)
    {
        return $this->andX(new Key($field, new KeyNotExists($key)));
    }

    public function andKeyNull($field, $key)
    {
        return $this->andX(new Key($field, new Key($key, new Null())));
    }

    public function andKeyNotNull($field, $key)
    {
        return $this->andX(new Key($field, new Key($key, new NotNull())));
    }

    public function andKeyEmpty($field, $key)
    {
        return $this->andX(new Key($field, new Key($key, new IsEmpty())));
    }

    public function andKeyNotEmpty($field, $key)
    {
        return $this->andX(new Key($field, new Key($key, new NotEmpty())));
    }

    public function andKeyTrue($field, $key, $strict = true)
    {
        return $this->andX(new Key($field, new Key($key, new True($strict))));
    }

    public function andKeyFalse($field, $key, $strict = true)
    {
        return $this->andX(new Key($field, new Key($key, new False($strict))));
    }

    public function andKeyEquals($field, $key, $value)
    {
        return $this->andX(new Key($field, new Key($key, new Equals($value))));
    }

    public function andKeyNotEquals($field, $key, $value)
    {
        return $this->andX(new Key($field, new Key($key, new NotEquals($value))));
    }

    public function andKeySame($field, $key, $value)
    {
        return $this->andX(new Key($field, new Key($key, new Same($value))));
    }

    public function andKeyNotSame($field, $key, $value)
    {
        return $this->andX(new Key($field, new Key($key, new NotSame($value))));
    }

    public function andKeyGreaterThan($field, $key, $value)
    {
        return $this->andX(new Key($field, new Key($key, new GreaterThan($value))));
    }

    public function andKeyGreaterThanEqual($field, $key, $value)
    {
        return $this->andX(new Key($field, new Key($key, new GreaterThanEqual($value))));
    }

    public function andKeyLessThan($field, $key, $value)
    {
        return $this->andX(new Key($field, new Key($key, new LessThan($value))));
    }

    public function andKeyLessThanEqual($field, $key, $value)
    {
        return $this->andX(new Key($field, new Key($key, new LessThanEqual($value))));
    }

    public function andKeyOneOf($field, $key, array $values, $strict = true)
    {
        return $this->andX(new Key($field, new Key($key, new OneOf($values, $strict))));
    }

    public function andKeyMatches($field, $key, $regExp)
    {
        return $this->andX(new Key($field, new Key($key, new Matches($regExp))));
    }

    public function andKeyStartsWith($field, $key, $prefix)
    {
        return $this->andX(new Key($field, new Key($key, new StartsWith($prefix))));
    }

    public function andKeyEndsWith($field, $key, $suffix)
    {
        return $this->andX(new Key($field, new Key($key, new EndsWith($suffix))));
    }

    public function orX(Criteria $x)
    {
        if ($this->equals($x)) {
            return $this;
        }

        return new Disjunction(array($this, $x));
    }

    public function orNot(Criteria $criteria)
    {
        return $this->orX(new Not($criteria));
    }

    public function orNull($field)
    {
        return $this->orX(new Key($field, new Null()));
    }

    public function orNotNull($field)
    {
        return $this->orX(new Key($field, new NotNull()));
    }

    public function orEmpty($field)
    {
        return $this->orX(new Key($field, new IsEmpty()));
    }

    public function orNotEmpty($field)
    {
        return $this->orX(new Key($field, new NotEmpty()));
    }

    public function orTrue($field, $strict = true)
    {
        return $this->orX(new Key($field, new True($strict)));
    }

    public function orFalse($field, $strict = true)
    {
        return $this->orX(new Key($field, new False($strict)));
    }

    public function orEquals($field, $value)
    {
        return $this->orX(new Key($field, new Equals($value)));
    }

    public function orNotEquals($field, $value)
    {
        return $this->orX(new Key($field, new NotEquals($value)));
    }

    public function orSame($field, $value)
    {
        return $this->orX(new Key($field, new Same($value)));
    }

    public function orNotSame($field, $value)
    {
        return $this->orX(new Key($field, new NotSame($value)));
    }

    public function orGreaterThan($field, $value)
    {
        return $this->orX(new Key($field, new GreaterThan($value)));
    }

    public function orGreaterThanEqual($field, $value)
    {
        return $this->orX(new Key($field, new GreaterThanEqual($value)));
    }

    public function orLessThan($field, $value)
    {
        return $this->orX(new Key($field, new LessThan($value)));
    }

    public function orLessThanEqual($field, $value)
    {
        return $this->orX(new Key($field, new LessThanEqual($value)));
    }

    public function orOneOf($field, array $values, $strict = true)
    {
        return $this->orX(new Key($field, new OneOf($values, $strict)));
    }

    public function orMatches($field, $regExp)
    {
        return $this->orX(new Key($field, new Matches($regExp)));
    }

    public function orStartsWith($field, $prefix)
    {
        return $this->orX(new Key($field, new StartsWith($prefix)));
    }

    public function orEndsWith($field, $suffix)
    {
        return $this->orX(new Key($field, new EndsWith($suffix)));
    }

    public function orKey($field, $key, Criteria $criteria)
    {
        return $this->orX(new Key($field, new Key($key, $criteria)));
    }

    public function orKeyExists($field, $key)
    {
        return $this->orX(new Key($field, new KeyExists($key)));
    }

    public function orKeyNotExists($field, $key)
    {
        return $this->orX(new Key($field, new KeyNotExists($key)));
    }

    public function orKeyNull($field, $key)
    {
        return $this->orX(new Key($field, new Key($key, new Null())));
    }

    public function orKeyNotNull($field, $key)
    {
        return $this->orX(new Key($field, new Key($key, new NotNull())));
    }

    public function orKeyEmpty($field, $key)
    {
        return $this->orX(new Key($field, new Key($key, new IsEmpty())));
    }

    public function orKeyNotEmpty($field, $key)
    {
        return $this->orX(new Key($field, new Key($key, new NotEmpty())));
    }

    public function orKeyTrue($field, $key, $strict = true)
    {
        return $this->orX(new Key($field, new Key($key, new True($strict))));
    }

    public function orKeyFalse($field, $key, $strict = true)
    {
        return $this->orX(new Key($field, new Key($key, new False($strict))));
    }

    public function orKeyEquals($field, $key, $value)
    {
        return $this->orX(new Key($field, new Key($key, new Equals($value))));
    }

    public function orKeyNotEquals($field, $key, $value)
    {
        return $this->orX(new Key($field, new Key($key, new NotEquals($value))));
    }

    public function orKeySame($field, $key, $value)
    {
        return $this->orX(new Key($field, new Key($key, new Same($value))));
    }

    public function orKeyNotSame($field, $key, $value)
    {
        return $this->orX(new Key($field, new Key($key, new NotSame($value))));
    }

    public function orKeyGreaterThan($field, $key, $value)
    {
        return $this->orX(new Key($field, new Key($key, new GreaterThan($value))));
    }

    public function orKeyGreaterThanEqual($field, $key, $value)
    {
        return $this->orX(new Key($field, new Key($key, new GreaterThanEqual($value))));
    }

    public function orKeyLessThan($field, $key, $value)
    {
        return $this->orX(new Key($field, new Key($key, new LessThan($value))));
    }

    public function orKeyLessThanEqual($field, $key, $value)
    {
        return $this->orX(new Key($field, new Key($key, new LessThanEqual($value))));
    }

    public function orKeyOneOf($field, $key, array $values, $strict = true)
    {
        return $this->orX(new Key($field, new Key($key, new OneOf($values, $strict))));
    }

    public function orKeyMatches($field, $key, $regExp)
    {
        return $this->orX(new Key($field, new Key($key, new Matches($regExp))));
    }

    public function orKeyStartsWith($field, $key, $prefix)
    {
        return $this->orX(new Key($field, new Key($key, new StartsWith($prefix))));
    }

    public function orKeyEndsWith($field, $key, $suffix)
    {
        return $this->orX(new Key($field, new Key($key, new EndsWith($suffix))));
    }
}
