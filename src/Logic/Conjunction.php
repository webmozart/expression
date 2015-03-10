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
class Conjunction implements Expression
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
            if ($conjunct->equals($expr)) {
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
    public function equals(Expression $other)
    {
        if (get_class($this) !== get_class($other)) {
            return false;
        }

        /** @var static $other */
        $leftConjuncts = $this->conjuncts;
        $rightConjuncts = $other->conjuncts;

        foreach ($leftConjuncts as $leftConjunct) {
            foreach ($rightConjuncts as $j => $rightConjunct) {
                if ($leftConjunct->equals($rightConjunct)) {
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
        return '('.implode(' && ', $this->conjuncts).')';
    }

    public function __toString()
    {
        return $this->toString();
    }
}
