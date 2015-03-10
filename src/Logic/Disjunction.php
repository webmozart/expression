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

use Webmozart\Expression\Comparison\EndsWith;
use Webmozart\Expression\Comparison\Equals;
use Webmozart\Expression\Comparison\False;
use Webmozart\Expression\Comparison\GreaterThan;
use Webmozart\Expression\Comparison\GreaterThanEqual;
use Webmozart\Expression\Comparison\IsEmpty;
use Webmozart\Expression\Comparison\LessThan;
use Webmozart\Expression\Comparison\LessThanEqual;
use Webmozart\Expression\Comparison\Matches;
use Webmozart\Expression\Comparison\NotEmpty;
use Webmozart\Expression\Comparison\NotEquals;
use Webmozart\Expression\Comparison\NotNull;
use Webmozart\Expression\Comparison\NotSame;
use Webmozart\Expression\Comparison\Null;
use Webmozart\Expression\Comparison\OneOf;
use Webmozart\Expression\Comparison\Same;
use Webmozart\Expression\Comparison\StartsWith;
use Webmozart\Expression\Comparison\True;
use Webmozart\Expression\Expression;
use Webmozart\Expression\Key\Key;
use Webmozart\Expression\Key\KeyExists;
use Webmozart\Expression\Key\KeyNotExists;

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
    private $disjuncts;

    /**
     * Creates a disjunction of the given expressions.
     *
     * @param Expression[] $disjuncts The disjuncts.
     */
    public function __construct(array $disjuncts = array())
    {
        $this->disjuncts = $disjuncts;
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

    public function orX(Expression $x)
    {
        foreach ($this->disjuncts as $disjunct) {
            if ($disjunct->equals($x)) {
                return $this;
            }
        }

        $disjuncts = $this->disjuncts;
        $disjuncts[] = $x;

        return new Disjunction($disjuncts);
    }

    public function orNot(Expression $expr)
    {
        return $this->orX(new Not($expr));
    }

    public function orNull($field)
    {
        return $this->orX(new Key($field, new Null()));
    }

    public function orNotNull($field)
    {
        return $this->orX(new Key($field, new NotNull()));
    }

    public function orEmpty($field)
    {
        return $this->orX(new Key($field, new IsEmpty()));
    }

    public function orNotEmpty($field)
    {
        return $this->orX(new Key($field, new NotEmpty()));
    }

    public function orTrue($field, $strict = true)
    {
        return $this->orX(new Key($field, new True($strict)));
    }

    public function orFalse($field, $strict = true)
    {
        return $this->orX(new Key($field, new False($strict)));
    }

    public function orEquals($field, $value)
    {
        return $this->orX(new Key($field, new Equals($value)));
    }

    public function orNotEquals($field, $value)
    {
        return $this->orX(new Key($field, new NotEquals($value)));
    }

    public function orSame($field, $value)
    {
        return $this->orX(new Key($field, new Same($value)));
    }

    public function orNotSame($field, $value)
    {
        return $this->orX(new Key($field, new NotSame($value)));
    }

    public function orGreaterThan($field, $value)
    {
        return $this->orX(new Key($field, new GreaterThan($value)));
    }

    public function orGreaterThanEqual($field, $value)
    {
        return $this->orX(new Key($field, new GreaterThanEqual($value)));
    }

    public function orLessThan($field, $value)
    {
        return $this->orX(new Key($field, new LessThan($value)));
    }

    public function orLessThanEqual($field, $value)
    {
        return $this->orX(new Key($field, new LessThanEqual($value)));
    }

    public function orOneOf($field, array $values, $strict = true)
    {
        return $this->orX(new Key($field, new OneOf($values, $strict)));
    }

    public function orMatches($field, $regExp)
    {
        return $this->orX(new Key($field, new Matches($regExp)));
    }

    public function orStartsWith($field, $prefix)
    {
        return $this->orX(new Key($field, new StartsWith($prefix)));
    }

    public function orEndsWith($field, $suffix)
    {
        return $this->orX(new Key($field, new EndsWith($suffix)));
    }

    public function orKey($field, $key, Expression $expr)
    {
        return $this->orX(new Key($field, new Key($key, $expr)));
    }

    public function orKeyExists($field, $key)
    {
        return $this->orX(new Key($field, new KeyExists($key)));
    }

    public function orKeyNotExists($field, $key)
    {
        return $this->orX(new Key($field, new KeyNotExists($key)));
    }

    public function orKeyNull($field, $key)
    {
        return $this->orX(new Key($field, new Key($key, new Null())));
    }

    public function orKeyNotNull($field, $key)
    {
        return $this->orX(new Key($field, new Key($key, new NotNull())));
    }

    public function orKeyEmpty($field, $key)
    {
        return $this->orX(new Key($field, new Key($key, new IsEmpty())));
    }

    public function orKeyNotEmpty($field, $key)
    {
        return $this->orX(new Key($field, new Key($key, new NotEmpty())));
    }

    public function orKeyTrue($field, $key, $strict = true)
    {
        return $this->orX(new Key($field, new Key($key, new True($strict))));
    }

    public function orKeyFalse($field, $key, $strict = true)
    {
        return $this->orX(new Key($field, new Key($key, new False($strict))));
    }

    public function orKeyEquals($field, $key, $value)
    {
        return $this->orX(new Key($field, new Key($key, new Equals($value))));
    }

    public function orKeyNotEquals($field, $key, $value)
    {
        return $this->orX(new Key($field, new Key($key, new NotEquals($value))));
    }

    public function orKeySame($field, $key, $value)
    {
        return $this->orX(new Key($field, new Key($key, new Same($value))));
    }

    public function orKeyNotSame($field, $key, $value)
    {
        return $this->orX(new Key($field, new Key($key, new NotSame($value))));
    }

    public function orKeyGreaterThan($field, $key, $value)
    {
        return $this->orX(new Key($field, new Key($key, new GreaterThan($value))));
    }

    public function orKeyGreaterThanEqual($field, $key, $value)
    {
        return $this->orX(new Key($field, new Key($key, new GreaterThanEqual($value))));
    }

    public function orKeyLessThan($field, $key, $value)
    {
        return $this->orX(new Key($field, new Key($key, new LessThan($value))));
    }

    public function orKeyLessThanEqual($field, $key, $value)
    {
        return $this->orX(new Key($field, new Key($key, new LessThanEqual($value))));
    }

    public function orKeyOneOf($field, $key, array $values, $strict = true)
    {
        return $this->orX(new Key($field, new Key($key, new OneOf($values, $strict))));
    }

    public function orKeyMatches($field, $key, $regExp)
    {
        return $this->orX(new Key($field, new Key($key, new Matches($regExp))));
    }

    public function orKeyStartsWith($field, $key, $prefix)
    {
        return $this->orX(new Key($field, new Key($key, new StartsWith($prefix))));
    }

    public function orKeyEndsWith($field, $key, $suffix)
    {
        return $this->orX(new Key($field, new Key($key, new EndsWith($suffix))));
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
}
