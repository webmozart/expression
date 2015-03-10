<?php

/*
 * This file is part of the webmozart/expression package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Expression\Logic;

use Webmozart\Expression\Comparison\EndsWith;
use Webmozart\Expression\Comparison\Equals;
use Webmozart\Expression\Comparison\False;
use Webmozart\Expression\Comparison\GreaterThan;
use Webmozart\Expression\Comparison\GreaterThanEqual;
use Webmozart\Expression\Comparison\IsEmpty;
use Webmozart\Expression\Comparison\LessThan;
use Webmozart\Expression\Comparison\LessThanEqual;
use Webmozart\Expression\Comparison\Matches;
use Webmozart\Expression\Comparison\NotEmpty;
use Webmozart\Expression\Comparison\NotEquals;
use Webmozart\Expression\Comparison\NotNull;
use Webmozart\Expression\Comparison\NotSame;
use Webmozart\Expression\Comparison\Null;
use Webmozart\Expression\Comparison\OneOf;
use Webmozart\Expression\Comparison\Same;
use Webmozart\Expression\Comparison\StartsWith;
use Webmozart\Expression\Comparison\True;
use Webmozart\Expression\Expression;
use Webmozart\Expression\Key\Key;
use Webmozart\Expression\Key\KeyExists;
use Webmozart\Expression\Key\KeyNotExists;

/**
 * A logical literal.
 *
 * In pure logics, a literal is any part of a formula that does not contain
 * "and" or "or" operators. In this package, the definition of a literal is
 * widened to any logical expression that is *not* a conjunction/disjunction.
 *
 * Examples:
 *
 *  * not endsWith(".css")
 *  * greaterThan(0)
 *  * not (greaterThan(0) and lessThan(120))
 *
 * The following examples are *not* literals:
 *
 *  * greaterThan(0) and lessThan(120)
 *  * oneOf(["A", "B", "C]) or null()
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
abstract class Literal implements Expression
{
    /**
     * {@inheritdoc}
     */
    public function equals(Expression $other)
    {
        return $other == $this;
    }

    public function andX(Expression $expr)
    {
        if ($this->equals($expr)) {
            return $this;
        }

        return new Conjunction(array($this, $expr));
    }

    public function andNot(Expression $expr)
    {
        return $this->andX(new Not($expr));
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

    public function andKey($field, $key, Expression $expr)
    {
        return $this->andX(new Key($field, new Key($key, $expr)));
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

    public function orX(Expression $expr)
    {
        if ($this->equals($expr)) {
            return $this;
        }

        return new Disjunction(array($this, $expr));
    }

    public function orNot(Expression $expr)
    {
        return $this->orX(new Not($expr));
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

    public function orKey($field, $key, Expression $expr)
    {
        return $this->orX(new Key($field, new Key($key, $expr)));
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
