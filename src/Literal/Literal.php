<?php

/*
 * This file is part of the webmozart/criteria package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webmozart\Criteria\Literal;

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
use Webmozart\Criteria\Criteria;
use Webmozart\Criteria\Formula\Conjunction;
use Webmozart\Criteria\Formula\Disjunction;

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
        return new Conjunction(array($this, $x));
    }

    public function andNot(Criteria $criteria)
    {
        return new Conjunction(array($this, new Not($criteria)));
    }

    public function andNull($field)
    {
        return new Conjunction(array($this, new Null($field)));
    }

    public function andNotNull($field)
    {
        return new Conjunction(array($this, new NotNull($field)));
    }

    public function andEmpty($field)
    {
        return new Conjunction(array($this, new IsEmpty($field)));
    }

    public function andNotEmpty($field)
    {
        return new Conjunction(array($this, new NotEmpty($field)));
    }

    public function andTrue($field, $strict = true)
    {
        return new Conjunction(array($this, new True($field, $strict)));
    }

    public function andFalse($field, $strict = true)
    {
        return new Conjunction(array($this, new False($field, $strict)));
    }

    public function andEquals($field, $value)
    {
        return new Conjunction(array($this, new Equals($field, $value)));
    }

    public function andNotEquals($field, $value)
    {
        return new Conjunction(array($this, new NotEquals($field, $value)));
    }

    public function andSame($field, $value)
    {
        return new Conjunction(array($this, new Same($field, $value)));
    }

    public function andNotSame($field, $value)
    {
        return new Conjunction(array($this, new NotSame($field, $value)));
    }

    public function andGreaterThan($field, $value)
    {
        return new Conjunction(array($this, new GreaterThan($field, $value)));
    }

    public function andGreaterThanEqual($field, $value)
    {
        return new Conjunction(array($this, new GreaterThanEqual($field, $value)));
    }

    public function andLessThan($field, $value)
    {
        return new Conjunction(array($this, new LessThan($field, $value)));
    }

    public function andLessThanEqual($field, $value)
    {
        return new Conjunction(array($this, new LessThanEqual($field, $value)));
    }

    public function andOneOf($field, array $values, $strict = true)
    {
        return new Conjunction(array($this, new OneOf($field, $values, $strict)));
    }

    public function andMatches($field, $regExp)
    {
        return new Conjunction(array($this, new Matches($field, $regExp)));
    }

    public function andStartsWith($field, $prefix)
    {
        return new Conjunction(array($this, new StartsWith($field, $prefix)));
    }

    public function andEndsWith($field, $suffix)
    {
        return new Conjunction(array($this, new EndsWith($field, $suffix)));
    }

    public function orX(Criteria $x)
    {
        return new Disjunction(array($this, $x));
    }

    public function orNot(Criteria $criteria)
    {
        return new Disjunction(array($this, new Not($criteria)));
    }

    public function orNull($field)
    {
        return new Disjunction(array($this, new Null($field)));
    }

    public function orNotNull($field)
    {
        return new Disjunction(array($this, new NotNull($field)));
    }

    public function orEmpty($field)
    {
        return new Disjunction(array($this, new IsEmpty($field)));
    }

    public function orNotEmpty($field)
    {
        return new Disjunction(array($this, new NotEmpty($field)));
    }

    public function orTrue($field, $strict = true)
    {
        return new Disjunction(array($this, new True($field, $strict)));
    }

    public function orFalse($field, $strict = true)
    {
        return new Disjunction(array($this, new False($field, $strict)));
    }

    public function orEquals($field, $value)
    {
        return new Disjunction(array($this, new Equals($field, $value)));
    }

    public function orNotEquals($field, $value)
    {
        return new Disjunction(array($this, new NotEquals($field, $value)));
    }

    public function orSame($field, $value)
    {
        return new Disjunction(array($this, new Same($field, $value)));
    }

    public function orNotSame($field, $value)
    {
        return new Disjunction(array($this, new NotSame($field, $value)));
    }

    public function orGreaterThan($field, $value)
    {
        return new Disjunction(array($this, new GreaterThan($field, $value)));
    }

    public function orGreaterThanEqual($field, $value)
    {
        return new Disjunction(array($this, new GreaterThanEqual($field, $value)));
    }

    public function orLessThan($field, $value)
    {
        return new Disjunction(array($this, new LessThan($field, $value)));
    }

    public function orLessThanEqual($field, $value)
    {
        return new Disjunction(array($this, new LessThanEqual($field, $value)));
    }

    public function orOneOf($field, array $values, $strict = true)
    {
        return new Disjunction(array($this, new OneOf($field, $values, $strict)));
    }

    public function orMatches($field, $regExp)
    {
        return new Disjunction(array($this, new Matches($field, $regExp)));
    }

    public function orStartsWith($field, $prefix)
    {
        return new Disjunction(array($this, new StartsWith($field, $prefix)));
    }

    public function orEndsWith($field, $suffix)
    {
        return new Disjunction(array($this, new EndsWith($field, $suffix)));
    }
}
