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

use Webmozart\Expression\Expr;
use Webmozart\Expression\Expression;

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
 *  * in(["A", "B", "C]) or null()
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
abstract class Literal implements Expression
{
    public function andX(Expression $expr)
    {
        if ($expr instanceof Valid) {
            return $this;
        } elseif ($expr instanceof Invalid) {
            return $expr;
        }

        if ($this->equivalentTo($expr)) {
            return $this;
        }

        return new Conjunction(array($this, $expr));
    }

    public function andNot(Expression $expr)
    {
        return $this->andX(Expr::not($expr));
    }

    public function andValid()
    {
        return $this;
    }

    public function andInvalid()
    {
        return Expr::invalid();
    }

    public function andKey($key, Expression $expr)
    {
        return $this->andX(Expr::key($key, $expr));
    }

    public function andAtLeast($count, Expression $expr)
    {
        return $this->andX(Expr::atLeast($count, $expr));
    }

    public function andAtMost($count, Expression $expr)
    {
        return $this->andX(Expr::atMost($count, $expr));
    }

    public function andExactly($count, Expression $expr)
    {
        return $this->andX(Expr::exactly($count, $expr));
    }

    public function andAll(Expression $expr)
    {
        return $this->andX(Expr::all($expr));
    }

    public function andCount(Expression $expr)
    {
        return $this->andX(Expr::count($expr));
    }

    public function andNull($key = null)
    {
        return $this->andX(Expr::null($key));
    }

    public function andNotNull($key = null)
    {
        return $this->andX(Expr::notNull($key));
    }

    public function andEmpty($key = null)
    {
        return $this->andX(Expr::isEmpty($key));
    }

    public function andNotEmpty($key = null)
    {
        return $this->andX(Expr::notEmpty($key));
    }

    public function andTrue($key = null)
    {
        return $this->andX(Expr::true($key));
    }

    public function andFalse($key = null)
    {
        return $this->andX(Expr::false($key));
    }

    public function andEquals($value, $key = null)
    {
        return $this->andX(Expr::equals($value, $key));
    }

    public function andNotEquals($value, $key = null)
    {
        return $this->andX(Expr::notEquals($value, $key));
    }

    public function andSame($value, $key = null)
    {
        return $this->andX(Expr::same($value, $key));
    }

    public function andNotSame($value, $key = null)
    {
        return $this->andX(Expr::notSame($value, $key));
    }

    public function andGreaterThan($value, $key = null)
    {
        return $this->andX(Expr::greaterThan($value, $key));
    }

    public function andGreaterThanEqual($value, $key = null)
    {
        return $this->andX(Expr::greaterThanEqual($value, $key));
    }

    public function andLessThan($value, $key = null)
    {
        return $this->andX(Expr::lessThan($value, $key));
    }

    public function andLessThanEqual($value, $key = null)
    {
        return $this->andX(Expr::lessThanEqual($value, $key));
    }

    public function andIn(array $values, $key = null)
    {
        return $this->andX(Expr::in($values, $key));
    }

    public function andMatches($regExp, $key = null)
    {
        return $this->andX(Expr::matches($regExp, $key));
    }

    public function andStartsWith($prefix, $key = null)
    {
        return $this->andX(Expr::startsWith($prefix, $key));
    }

    public function andEndsWith($suffix, $key = null)
    {
        return $this->andX(Expr::endsWith($suffix, $key));
    }

    public function andKeyExists($keyName, $key = null)
    {
        return $this->andX(Expr::keyExists($keyName, $key));
    }

    public function andKeyNotExists($keyName, $key = null)
    {
        return $this->andX(Expr::keyNotExists($keyName, $key));
    }

    public function orX(Expression $expr)
    {
        if ($expr instanceof Invalid) {
            return $this;
        } elseif ($expr instanceof Valid) {
            return $expr;
        }

        if ($this->equivalentTo($expr)) {
            return $this;
        }

        return new Disjunction(array($this, $expr));
    }

    public function orNot(Expression $expr)
    {
        return $this->orX(Expr::not($expr));
    }

    public function orValid()
    {
        return Expr::valid();
    }

    public function orInvalid()
    {
        return $this;
    }

    public function orKey($key, Expression $expr)
    {
        return $this->orX(Expr::key($key, $expr));
    }

    public function orAtLeast($count, Expression $expr)
    {
        return $this->orX(Expr::atLeast($count, $expr));
    }

    public function orAtMost($count, Expression $expr)
    {
        return $this->orX(Expr::atMost($count, $expr));
    }

    public function orExactly($count, Expression $expr)
    {
        return $this->orX(Expr::exactly($count, $expr));
    }

    public function orAll(Expression $expr)
    {
        return $this->orX(Expr::all($expr));
    }

    public function orCount(Expression $expr)
    {
        return $this->orX(Expr::count($expr));
    }

    public function orNull($key = null)
    {
        return $this->orX(Expr::null($key));
    }

    public function orNotNull($key = null)
    {
        return $this->orX(Expr::notNull($key));
    }

    public function orEmpty($key = null)
    {
        return $this->orX(Expr::isEmpty($key));
    }

    public function orNotEmpty($key = null)
    {
        return $this->orX(Expr::notEmpty($key));
    }

    public function orTrue($key = null)
    {
        return $this->orX(Expr::true($key));
    }

    public function orFalse($key = null)
    {
        return $this->orX(Expr::false($key));
    }

    public function orEquals($value, $key = null)
    {
        return $this->orX(Expr::equals($value, $key));
    }

    public function orNotEquals($value, $key = null)
    {
        return $this->orX(Expr::notEquals($value, $key));
    }

    public function orSame($value, $key = null)
    {
        return $this->orX(Expr::same($value, $key));
    }

    public function orNotSame($value, $key = null)
    {
        return $this->orX(Expr::notSame($value, $key));
    }

    public function orGreaterThan($value, $key = null)
    {
        return $this->orX(Expr::greaterThan($value, $key));
    }

    public function orGreaterThanEqual($value, $key = null)
    {
        return $this->orX(Expr::greaterThanEqual($value, $key));
    }

    public function orLessThan($value, $key = null)
    {
        return $this->orX(Expr::lessThan($value, $key));
    }

    public function orLessThanEqual($value, $key = null)
    {
        return $this->orX(Expr::lessThanEqual($value, $key));
    }

    public function orIn(array $values, $key = null)
    {
        return $this->orX(Expr::in($values, $key));
    }

    public function orMatches($regExp, $key = null)
    {
        return $this->orX(Expr::matches($regExp, $key));
    }

    public function orStartsWith($prefix, $key = null)
    {
        return $this->orX(Expr::startsWith($prefix, $key));
    }

    public function orEndsWith($suffix, $key = null)
    {
        return $this->orX(Expr::endsWith($suffix, $key));
    }

    public function orKeyExists($keyName, $key = null)
    {
        return $this->orX(Expr::keyExists($keyName, $key));
    }

    public function orKeyNotExists($keyName, $key = null)
    {
        return $this->orX(Expr::keyNotExists($keyName, $key));
    }

    public function __toString()
    {
        return $this->toString();
    }
}
