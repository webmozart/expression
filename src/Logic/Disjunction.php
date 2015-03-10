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
 * A disjunction of expressions.
 *
 * A disjunction is a set of {@link Expression} instances connected by logical
 * "or" operators.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class Disjunction implements Expression
{
    /**
     * @var Expression[]
     */
    private $disjuncts = array();

    /**
     * Creates a disjunction of the given expressions.
     *
     * @param Expression[] $disjuncts The disjuncts.
     */
    public function __construct(array $disjuncts = array())
    {
        foreach ($disjuncts as $disjunct) {
            if ($disjunct instanceof self) {
                foreach ($disjunct->disjuncts as $expr) {
                    // $disjunct is guaranteed not to contain Disjunctions
                    $this->disjuncts[] = $expr;
                }
            } else {
                $this->disjuncts[] = $disjunct;
            }
        }
    }

    /**
     * Returns the disjuncts of the disjunction.
     *
     * @return Expression[] The disjuncts.
     */
    public function getDisjuncts()
    {
        return $this->disjuncts;
    }

    public function orX(Expression $expr)
    {
        foreach ($this->disjuncts as $disjunct) {
            if ($disjunct->equals($expr)) {
                return $this;
            }
        }

        $disjuncts = $this->disjuncts;

        if ($expr instanceof self) {
            $disjuncts = array_merge($disjuncts, $expr->disjuncts);
        } else {
            $disjuncts[] = $expr;
        }

        return new Disjunction($disjuncts);
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

    /**
     * {@inheritdoc}
     */
    public function evaluate($values)
    {
        foreach ($this->disjuncts as $expr) {
            if ($expr->evaluate($values)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function equals(Expression $other)
    {
        if (get_class($this) !== get_class($other)) {
            return false;
        }

        /** @var static $other */
        $leftDisjuncts = $this->disjuncts;
        $rightDisjuncts = $other->disjuncts;

        foreach ($leftDisjuncts as $leftDisjunct) {
            foreach ($rightDisjuncts as $j => $rightDisjunct) {
                if ($leftDisjunct->equals($rightDisjunct)) {
                    unset($rightDisjuncts[$j]);
                    continue 2;
                }
            }

            // $leftDisjunct was not found in $rightDisjuncts
            return false;
        }

        // All $leftDisjuncts were found. Check if any $rightDisjuncts are left
        return 0 === count($rightDisjuncts);
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return '('.implode(' || ', $this->disjuncts).')';
    }

    public function __toString()
    {
        return $this->toString();
    }
}
