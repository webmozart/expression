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
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
abstract class Literal implements Expression
{
    public function andX(Expression $expr)
    {
        if ($expr instanceof AlwaysTrue) {
            return $this;
        } elseif ($expr instanceof AlwaysFalse) {
            return $expr;
        }

        if ($this->equivalentTo($expr)) {
            return $this;
        }

        return new AndX(array($this, $expr));
    }

    public function andNot(Expression $expr)
    {
        return $this->andX(Expr::not($expr));
    }

    public function andTrue()
    {
        return $this;
    }

    public function andFalse()
    {
        return Expr::false();
    }

    public function andKey($keyName, Expression $expr)
    {
        return $this->andX(Expr::key($keyName, $expr));
    }

    public function andMethod($methodName, $args)
    {
        return $this->andX(call_user_func_array(array('Webmozart\Expression\Expr', 'method'), func_get_args()));
    }

    public function andProperty($propertyName, Expression $expr)
    {
        return $this->andX(Expr::property($propertyName, $expr));
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

    public function andNull()
    {
        return $this->andX(Expr::null());
    }

    public function andNotNull()
    {
        return $this->andX(Expr::notNull());
    }

    public function andEmpty()
    {
        return $this->andX(Expr::isEmpty());
    }

    public function andNotEmpty()
    {
        return $this->andX(Expr::notEmpty());
    }

    public function andInstanceOf($className)
    {
        return $this->andX(Expr::isInstanceOf($className));
    }

    public function andEquals($value)
    {
        return $this->andX(Expr::equals($value));
    }

    public function andNotEquals($value)
    {
        return $this->andX(Expr::notEquals($value));
    }

    public function andSame($value)
    {
        return $this->andX(Expr::same($value));
    }

    public function andNotSame($value)
    {
        return $this->andX(Expr::notSame($value));
    }

    public function andGreaterThan($value)
    {
        return $this->andX(Expr::greaterThan($value));
    }

    public function andGreaterThanEqual($value)
    {
        return $this->andX(Expr::greaterThanEqual($value));
    }

    public function andLessThan($value)
    {
        return $this->andX(Expr::lessThan($value));
    }

    public function andLessThanEqual($value)
    {
        return $this->andX(Expr::lessThanEqual($value));
    }

    public function andIn(array $values)
    {
        return $this->andX(Expr::in($values));
    }

    public function andMatches($regExp)
    {
        return $this->andX(Expr::matches($regExp));
    }

    public function andStartsWith($prefix)
    {
        return $this->andX(Expr::startsWith($prefix));
    }

    public function andEndsWith($suffix)
    {
        return $this->andX(Expr::endsWith($suffix));
    }

    public function andContains($string)
    {
        return $this->andX(Expr::contains($string));
    }

    public function andKeyExists($keyName)
    {
        return $this->andX(Expr::keyExists($keyName));
    }

    public function andKeyNotExists($keyName)
    {
        return $this->andX(Expr::keyNotExists($keyName));
    }

    public function orX(Expression $expr)
    {
        if ($expr instanceof AlwaysFalse) {
            return $this;
        } elseif ($expr instanceof AlwaysTrue) {
            return $expr;
        }

        if ($this->equivalentTo($expr)) {
            return $this;
        }

        return new OrX(array($this, $expr));
    }

    public function orNot(Expression $expr)
    {
        return $this->orX(Expr::not($expr));
    }

    public function orTrue()
    {
        return Expr::true();
    }

    public function orFalse()
    {
        return $this;
    }

    public function orKey($keyName, Expression $expr)
    {
        return $this->orX(Expr::key($keyName, $expr));
    }

    public function orMethod($methodName, $args)
    {
        return $this->orX(call_user_func_array(array('Webmozart\Expression\Expr', 'method'), func_get_args()));
    }

    public function orProperty($propertyName, Expression $expr)
    {
        return $this->orX(Expr::property($propertyName, $expr));
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

    public function orNull()
    {
        return $this->orX(Expr::null());
    }

    public function orNotNull()
    {
        return $this->orX(Expr::notNull());
    }

    public function orEmpty()
    {
        return $this->orX(Expr::isEmpty());
    }

    public function orNotEmpty()
    {
        return $this->orX(Expr::notEmpty());
    }

    public function orInstanceOf($className)
    {
        return $this->orX(Expr::isInstanceOf($className));
    }

    public function orEquals($value)
    {
        return $this->orX(Expr::equals($value));
    }

    public function orNotEquals($value)
    {
        return $this->orX(Expr::notEquals($value));
    }

    public function orSame($value)
    {
        return $this->orX(Expr::same($value));
    }

    public function orNotSame($value)
    {
        return $this->orX(Expr::notSame($value));
    }

    public function orGreaterThan($value)
    {
        return $this->orX(Expr::greaterThan($value));
    }

    public function orGreaterThanEqual($value)
    {
        return $this->orX(Expr::greaterThanEqual($value));
    }

    public function orLessThan($value)
    {
        return $this->orX(Expr::lessThan($value));
    }

    public function orLessThanEqual($value)
    {
        return $this->orX(Expr::lessThanEqual($value));
    }

    public function orIn(array $values)
    {
        return $this->orX(Expr::in($values));
    }

    public function orMatches($regExp)
    {
        return $this->orX(Expr::matches($regExp));
    }

    public function orStartsWith($prefix)
    {
        return $this->orX(Expr::startsWith($prefix));
    }

    public function orEndsWith($suffix)
    {
        return $this->orX(Expr::endsWith($suffix));
    }

    public function orContains($string)
    {
        return $this->orX(Expr::contains($string));
    }

    public function orKeyExists($keyName)
    {
        return $this->orX(Expr::keyExists($keyName));
    }

    public function orKeyNotExists($keyName)
    {
        return $this->orX(Expr::keyNotExists($keyName));
    }

    public function __toString()
    {
        return $this->toString();
    }
}
