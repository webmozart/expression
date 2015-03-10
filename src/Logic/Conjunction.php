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
    private $conjuncts;

    /**
     * Creates a conjunction of the given expressions.
     *
     * @param Expression[] $conjuncts The conjuncts.
     */
    public function __construct(array $conjuncts = array())
    {
        $this->conjuncts = $conjuncts;
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

        $this->conjuncts[] = $expr;

        return $this;
    }

    public function andNot(Expression $expr)
    {
        return $this->andX(new Not($expr));
    }

    public function andNull($field)
    {
        return $this->andX(new Key($field, new Null()));
    }

    public function andNotNull($field)
    {
        return $this->andX(new Key($field, new NotNull()));
    }

    public function andEmpty($field)
    {
        return $this->andX(new Key($field, new IsEmpty()));
    }

    public function andNotEmpty($field)
    {
        return $this->andX(new Key($field, new NotEmpty()));
    }

    public function andTrue($field, $strict = true)
    {
        return $this->andX(new Key($field, new True($strict)));
    }

    public function andFalse($field, $strict = true)
    {
        return $this->andX(new Key($field, new False($strict)));
    }

    public function andEquals($field, $value)
    {
        return $this->andX(new Key($field, new Equals($value)));
    }

    public function andNotEquals($field, $value)
    {
        return $this->andX(new Key($field, new NotEquals($value)));
    }

    public function andSame($field, $value)
    {
        return $this->andX(new Key($field, new Same($value)));
    }

    public function andNotSame($field, $value)
    {
        return $this->andX(new Key($field, new NotSame($value)));
    }

    public function andGreaterThan($field, $value)
    {
        return $this->andX(new Key($field, new GreaterThan($value)));
    }

    public function andGreaterThanEqual($field, $value)
    {
        return $this->andX(new Key($field, new GreaterThanEqual($value)));
    }

    public function andLessThan($field, $value)
    {
        return $this->andX(new Key($field, new LessThan($value)));
    }

    public function andLessThanEqual($field, $value)
    {
        return $this->andX(new Key($field, new LessThanEqual($value)));
    }

    public function andOneOf($field, array $values, $strict = true)
    {
        return $this->andX(new Key($field, new OneOf($values, $strict)));
    }

    public function andMatches($field, $regExp)
    {
        return $this->andX(new Key($field, new Matches($regExp)));
    }

    public function andStartsWith($field, $prefix)
    {
        return $this->andX(new Key($field, new StartsWith($prefix)));
    }

    public function andEndsWith($field, $suffix)
    {
        return $this->andX(new Key($field, new EndsWith($suffix)));
    }

    public function andKey($field, $key, Expression $expr)
    {
        return $this->andX(new Key($field, new Key($key, $expr)));
    }

    public function andKeyExists($field, $key)
    {
        return $this->andX(new Key($field, new KeyExists($key)));
    }

    public function andKeyNotExists($field, $key)
    {
        return $this->andX(new Key($field, new KeyNotExists($key)));
    }

    public function andKeyNull($field, $key)
    {
        return $this->andX(new Key($field, new Key($key, new Null())));
    }

    public function andKeyNotNull($field, $key)
    {
        return $this->andX(new Key($field, new Key($key, new NotNull())));
    }

    public function andKeyEmpty($field, $key)
    {
        return $this->andX(new Key($field, new Key($key, new IsEmpty())));
    }

    public function andKeyNotEmpty($field, $key)
    {
        return $this->andX(new Key($field, new Key($key, new NotEmpty())));
    }

    public function andKeyTrue($field, $key, $strict = true)
    {
        return $this->andX(new Key($field, new Key($key, new True($strict))));
    }

    public function andKeyFalse($field, $key, $strict = true)
    {
        return $this->andX(new Key($field, new Key($key, new False($strict))));
    }

    public function andKeyEquals($field, $key, $value)
    {
        return $this->andX(new Key($field, new Key($key, new Equals($value))));
    }

    public function andKeyNotEquals($field, $key, $value)
    {
        return $this->andX(new Key($field, new Key($key, new NotEquals($value))));
    }

    public function andKeySame($field, $key, $value)
    {
        return $this->andX(new Key($field, new Key($key, new Same($value))));
    }

    public function andKeyNotSame($field, $key, $value)
    {
        return $this->andX(new Key($field, new Key($key, new NotSame($value))));
    }

    public function andKeyGreaterThan($field, $key, $value)
    {
        return $this->andX(new Key($field, new Key($key, new GreaterThan($value))));
    }

    public function andKeyGreaterThanEqual($field, $key, $value)
    {
        return $this->andX(new Key($field, new Key($key, new GreaterThanEqual($value))));
    }

    public function andKeyLessThan($field, $key, $value)
    {
        return $this->andX(new Key($field, new Key($key, new LessThan($value))));
    }

    public function andKeyLessThanEqual($field, $key, $value)
    {
        return $this->andX(new Key($field, new Key($key, new LessThanEqual($value))));
    }

    public function andKeyOneOf($field, $key, array $values, $strict = true)
    {
        return $this->andX(new Key($field, new Key($key, new OneOf($values, $strict))));
    }

    public function andKeyMatches($field, $key, $regExp)
    {
        return $this->andX(new Key($field, new Key($key, new Matches($regExp))));
    }

    public function andKeyStartsWith($field, $key, $prefix)
    {
        return $this->andX(new Key($field, new Key($key, new StartsWith($prefix))));
    }

    public function andKeyEndsWith($field, $key, $suffix)
    {
        return $this->andX(new Key($field, new Key($key, new EndsWith($suffix))));
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
}
