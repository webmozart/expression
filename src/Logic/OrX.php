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
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class OrX implements Expression
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
        if ($expr instanceof AlwaysFalse) {
            return $this;
        } elseif ($expr instanceof AlwaysTrue) {
            return $expr;
        }

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

        return new self($disjuncts);
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

    public function orCount(Expression $expr)
    {
        return $this->orX(Expr::count($expr));
    }

    public function orAll(Expression $expr)
    {
        return $this->orX(Expr::all($expr));
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

        /* @var static $other */
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
        return implode(' || ', array_map(function (Expression $disjunct) {
            return $disjunct instanceof AndX ? '('.$disjunct->toString().')' : $disjunct->toString();
        }, $this->disjuncts));
    }

    public function __toString()
    {
        return $this->toString();
    }
}
