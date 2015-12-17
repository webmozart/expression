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
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class AndX implements Expression
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
        if ($expr instanceof AlwaysTrue) {
            return $this;
        } elseif ($expr instanceof AlwaysFalse) {
            return $expr;
        }

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

        return new self($conjuncts);
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

        /* @var static $other */
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
        return implode(' && ', array_map(function (Expression $conjunct) {
            return $conjunct instanceof OrX ? '('.$conjunct->toString().')' : $conjunct->toString();
        }, $this->conjuncts));
    }

    public function __toString()
    {
        return $this->toString();
    }
}
