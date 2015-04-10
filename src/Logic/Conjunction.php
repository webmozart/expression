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
 * "and" operators.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
final class Conjunction implements Expression
{
    /**
     * @var Expression[]
     */
    private $conjuncts = array();

    /**
     * Creates a conjunction of the given expressions.
     *
     * @param Expression[] $conjuncts The conjuncts.
     */
    public function __construct(array $conjuncts = array())
    {
        foreach ($conjuncts as $conjunct) {
            if ($conjunct instanceof self) {
                foreach ($conjunct->conjuncts as $expr) {
                    // $conjunct is guaranteed not to contain Conjunctions
                    $this->conjuncts[] = $expr;
                }
            } else {
                $this->conjuncts[] = $conjunct;
            }
        };
    }

    /**
     * Returns the conjuncts of the conjunction.
     *
     * @return Expression[] The conjuncts.
     */
    public function getConjuncts()
    {
        return $this->conjuncts;
    }

    public function andX(Expression $expr)
    {
        foreach ($this->conjuncts as $conjunct) {
            if ($conjunct->equivalentTo($expr)) {
                return $this;
            }
        }

        $conjuncts = $this->conjuncts;

        if ($expr instanceof self) {
            $conjuncts = array_merge($conjuncts, $expr->conjuncts);
        } else {
            $conjuncts[] = $expr;
        }

        return new Conjunction($conjuncts);
    }

    public function andNot(Expression $expr)
    {
        return $this->andX(Expr::not($expr));
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

    public function andOneOf(array $values, $key = null)
    {
        return $this->andX(Expr::oneOf($values, $key));
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

    /**
     * {@inheritdoc}
     */
    public function evaluate($values)
    {
        foreach ($this->conjuncts as $expr) {
            if (!$expr->evaluate($values)) {
                return false;
            }
        }

        return true;
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
        $leftConjuncts = $this->conjuncts;
        $rightConjuncts = $other->conjuncts;

        foreach ($leftConjuncts as $leftConjunct) {
            foreach ($rightConjuncts as $j => $rightConjunct) {
                if ($leftConjunct->equivalentTo($rightConjunct)) {
                    unset($rightConjuncts[$j]);
                    continue 2;
                }
            }

            // $leftConjunct was not found in $rightConjuncts
            return false;
        }

        // All $leftConjuncts were found. Check if any $rightConjuncts are left
        return 0 === count($rightConjuncts);
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return implode(' && ', $this->conjuncts);
    }

    public function __toString()
    {
        return $this->toString();
    }
}
