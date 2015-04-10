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
final class Disjunction implements Expression
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
            if ($disjunct->equivalentTo($expr)) {
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

    public function orValid()
    {
        return $this->orX(Expr::valid());
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

    public function orCount(Expression $expr)
    {
        return $this->orX(Expr::count($expr));
    }

    public function orAll(Expression $expr)
    {
        return $this->orX(Expr::all($expr));
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
    public function equivalentTo(Expression $other)
    {
        if (get_class($this) !== get_class($other)) {
            return false;
        }

        /** @var static $other */
        $leftDisjuncts = $this->disjuncts;
        $rightDisjuncts = $other->disjuncts;

        foreach ($leftDisjuncts as $leftDisjunct) {
            foreach ($rightDisjuncts as $j => $rightDisjunct) {
                if ($leftDisjunct->equivalentTo($rightDisjunct)) {
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
        return implode(' || ', $this->disjuncts);
    }

    public function __toString()
    {
        return $this->toString();
    }
}
