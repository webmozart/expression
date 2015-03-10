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
        return $this->andX(Expr::not($expr));
    }

    public function andNull($field)
    {
        return $this->andX(Expr::null($field));
    }

    public function andNotNull($field)
    {
        return $this->andX(Expr::notNull($field));
    }

    public function andEmpty($field)
    {
        return $this->andX(Expr::isEmpty($field));
    }

    public function andNotEmpty($field)
    {
        return $this->andX(Expr::notEmpty($field));
    }

    public function andTrue($field, $strict = true)
    {
        return $this->andX(Expr::true($field, $strict));
    }

    public function andFalse($field, $strict = true)
    {
        return $this->andX(Expr::false($field, $strict));
    }

    public function andEquals($field, $value)
    {
        return $this->andX(Expr::equals($field, $value));
    }

    public function andNotEquals($field, $value)
    {
        return $this->andX(Expr::notEquals($field, $value));
    }

    public function andSame($field, $value)
    {
        return $this->andX(Expr::same($field, $value));
    }

    public function andNotSame($field, $value)
    {
        return $this->andX(Expr::notSame($field, $value));
    }

    public function andGreaterThan($field, $value)
    {
        return $this->andX(Expr::greaterThan($field, $value));
    }

    public function andGreaterThanEqual($field, $value)
    {
        return $this->andX(Expr::greaterThanEqual($field, $value));
    }

    public function andLessThan($field, $value)
    {
        return $this->andX(Expr::lessThan($field, $value));
    }

    public function andLessThanEqual($field, $value)
    {
        return $this->andX(Expr::lessThanEqual($field, $value));
    }

    public function andOneOf($field, array $values, $strict = true)
    {
        return $this->andX(Expr::oneOf($field, $values, $strict));
    }

    public function andMatches($field, $regExp)
    {
        return $this->andX(Expr::matches($field, $regExp));
    }

    public function andStartsWith($field, $prefix)
    {
        return $this->andX(Expr::startsWith($field, $prefix));
    }

    public function andEndsWith($field, $suffix)
    {
        return $this->andX(Expr::endsWith($field, $suffix));
    }

    public function andKey($field, $key, Expression $expr)
    {
        return $this->andX(Expr::key($field, $key, $expr));
    }

    public function andKeyExists($field, $key)
    {
        return $this->andX(Expr::keyExists($field, $key));
    }

    public function andKeyNotExists($field, $key)
    {
        return $this->andX(Expr::keyNotExists($field, $key));
    }

    public function andKeyNull($field, $key)
    {
        return $this->andX(Expr::keyNull($field, $key));
    }

    public function andKeyNotNull($field, $key)
    {
        return $this->andX(Expr::keyNotNull($field, $key));
    }

    public function andKeyEmpty($field, $key)
    {
        return $this->andX(Expr::keyEmpty($field, $key));
    }

    public function andKeyNotEmpty($field, $key)
    {
        return $this->andX(Expr::keyNotEmpty($field, $key));
    }

    public function andKeyTrue($field, $key, $strict = true)
    {
        return $this->andX(Expr::keyTrue($field, $key, $strict));
    }

    public function andKeyFalse($field, $key, $strict = true)
    {
        return $this->andX(Expr::keyFalse($field, $key, $strict));
    }

    public function andKeyEquals($field, $key, $value)
    {
        return $this->andX(Expr::keyEquals($field, $key, $value));
    }

    public function andKeyNotEquals($field, $key, $value)
    {
        return $this->andX(Expr::keyNotEquals($field, $key, $value));
    }

    public function andKeySame($field, $key, $value)
    {
        return $this->andX(Expr::keySame($field, $key, $value));
    }

    public function andKeyNotSame($field, $key, $value)
    {
        return $this->andX(Expr::keyNotSame($field, $key, $value));
    }

    public function andKeyGreaterThan($field, $key, $value)
    {
        return $this->andX(Expr::keyGreaterThan($field, $key, $value));
    }

    public function andKeyGreaterThanEqual($field, $key, $value)
    {
        return $this->andX(Expr::keyGreaterThanEqual($field, $key, $value));
    }

    public function andKeyLessThan($field, $key, $value)
    {
        return $this->andX(Expr::keyLessThan($field, $key, $value));
    }

    public function andKeyLessThanEqual($field, $key, $value)
    {
        return $this->andX(Expr::keyLessThanEqual($field, $key, $value));
    }

    public function andKeyOneOf($field, $key, array $values, $strict = true)
    {
        return $this->andX(Expr::keyOneOf($field, $key, $values, $strict));
    }

    public function andKeyMatches($field, $key, $regExp)
    {
        return $this->andX(Expr::keyMatches($field, $key, $regExp));
    }

    public function andKeyStartsWith($field, $key, $prefix)
    {
        return $this->andX(Expr::keyStartsWith($field, $key, $prefix));
    }

    public function andKeyEndsWith($field, $key, $suffix)
    {
        return $this->andX(Expr::keyEndsWith($field, $key, $suffix));
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
        return $this->orX(Expr::not($expr));
    }

    public function orNull($field)
    {
        return $this->orX(Expr::null($field));
    }

    public function orNotNull($field)
    {
        return $this->orX(Expr::notNull($field));
    }

    public function orEmpty($field)
    {
        return $this->orX(Expr::isEmpty($field));
    }

    public function orNotEmpty($field)
    {
        return $this->orX(Expr::notEmpty($field));
    }

    public function orTrue($field, $strict = true)
    {
        return $this->orX(Expr::true($field, $strict));
    }

    public function orFalse($field, $strict = true)
    {
        return $this->orX(Expr::false($field, $strict));
    }

    public function orEquals($field, $value)
    {
        return $this->orX(Expr::equals($field, $value));
    }

    public function orNotEquals($field, $value)
    {
        return $this->orX(Expr::notEquals($field, $value));
    }

    public function orSame($field, $value)
    {
        return $this->orX(Expr::same($field, $value));
    }

    public function orNotSame($field, $value)
    {
        return $this->orX(Expr::notSame($field, $value));
    }

    public function orGreaterThan($field, $value)
    {
        return $this->orX(Expr::greaterThan($field, $value));
    }

    public function orGreaterThanEqual($field, $value)
    {
        return $this->orX(Expr::greaterThanEqual($field, $value));
    }

    public function orLessThan($field, $value)
    {
        return $this->orX(Expr::lessThan($field, $value));
    }

    public function orLessThanEqual($field, $value)
    {
        return $this->orX(Expr::lessThanEqual($field, $value));
    }

    public function orOneOf($field, array $values, $strict = true)
    {
        return $this->orX(Expr::oneOf($field, $values, $strict));
    }

    public function orMatches($field, $regExp)
    {
        return $this->orX(Expr::matches($field, $regExp));
    }

    public function orStartsWith($field, $prefix)
    {
        return $this->orX(Expr::startsWith($field, $prefix));
    }

    public function orEndsWith($field, $suffix)
    {
        return $this->orX(Expr::endsWith($field, $suffix));
    }

    public function orKey($field, $key, Expression $expr)
    {
        return $this->orX(Expr::key($field, $key, $expr));
    }

    public function orKeyExists($field, $key)
    {
        return $this->orX(Expr::keyExists($field, $key));
    }

    public function orKeyNotExists($field, $key)
    {
        return $this->orX(Expr::keyNotExists($field, $key));
    }

    public function orKeyNull($field, $key)
    {
        return $this->orX(Expr::keyNull($field, $key));
    }

    public function orKeyNotNull($field, $key)
    {
        return $this->orX(Expr::keyNotNull($field, $key));
    }

    public function orKeyEmpty($field, $key)
    {
        return $this->orX(Expr::keyEmpty($field, $key));
    }

    public function orKeyNotEmpty($field, $key)
    {
        return $this->orX(Expr::keyNotEmpty($field, $key));
    }

    public function orKeyTrue($field, $key, $strict = true)
    {
        return $this->orX(Expr::keyTrue($field, $key, $strict));
    }

    public function orKeyFalse($field, $key, $strict = true)
    {
        return $this->orX(Expr::keyFalse($field, $key, $strict));
    }

    public function orKeyEquals($field, $key, $value)
    {
        return $this->orX(Expr::keyEquals($field, $key, $value));
    }

    public function orKeyNotEquals($field, $key, $value)
    {
        return $this->orX(Expr::keyNotEquals($field, $key, $value));
    }

    public function orKeySame($field, $key, $value)
    {
        return $this->orX(Expr::keySame($field, $key, $value));
    }

    public function orKeyNotSame($field, $key, $value)
    {
        return $this->orX(Expr::keyNotSame($field, $key, $value));
    }

    public function orKeyGreaterThan($field, $key, $value)
    {
        return $this->orX(Expr::keyGreaterThan($field, $key, $value));
    }

    public function orKeyGreaterThanEqual($field, $key, $value)
    {
        return $this->orX(Expr::keyGreaterThanEqual($field, $key, $value));
    }

    public function orKeyLessThan($field, $key, $value)
    {
        return $this->orX(Expr::keyLessThan($field, $key, $value));
    }

    public function orKeyLessThanEqual($field, $key, $value)
    {
        return $this->orX(Expr::keyLessThanEqual($field, $key, $value));
    }

    public function orKeyOneOf($field, $key, array $values, $strict = true)
    {
        return $this->orX(Expr::keyOneOf($field, $key, $values, $strict));
    }

    public function orKeyMatches($field, $key, $regExp)
    {
        return $this->orX(Expr::keyMatches($field, $key, $regExp));
    }

    public function orKeyStartsWith($field, $key, $prefix)
    {
        return $this->orX(Expr::keyStartsWith($field, $key, $prefix));
    }

    public function orKeyEndsWith($field, $key, $suffix)
    {
        return $this->orX(Expr::keyEndsWith($field, $key, $suffix));
    }

    public function __toString()
    {
        return $this->toString();
    }
}
